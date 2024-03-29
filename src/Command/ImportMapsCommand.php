<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Map;
use App\Interfaces\GraphQLClientInterface;
use App\Interfaces\MapInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import:maps',
    description: 'Import or update maps from https://tarkov.dev./api',
)]
class ImportMapsCommand extends Command
{
    private EntityManagerInterface $em;
    private GraphQLClientInterface $client;

    public function __construct(EntityManagerInterface $em, GraphQLClientInterface $client) {
        parent::__construct();

        $this->em = $em;
        $this->client = $client;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('This command allows you to import or update maps from https://tarkov.dev./api')
            ->addOption('lang', 'l', InputArgument::OPTIONAL, 'Language', default: 'ru')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $lang = $input->getOption('lang');

        $query = <<< GRAPHQL
            {
                maps(lang: {$lang}) {
                    id,
                    name,
                    nameId,
                    normalizedName,
                    wiki,
                    description,
                    enemies,
                    raidDuration,
                    players
                    accessKeys { id, name }
                    accessKeysMinPlayerLevel
                    spawns {
                        zoneName
                        sides
                        categories
                    }
                }
            }
        GRAPHQL;

        try {
            $response = $this->client->query($query);
            $maps = $response['data']['maps'];
        } catch (Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        if (null === $maps) {
            $io->warning('Nothing to import or update.');
        }

        $progressBar = new ProgressBar($output, count($maps));
        $progressBar->advance(0);
        $mapRepository = $this->em->getRepository(Map::class);

        foreach ($maps as $map) {
            list($minPlayers, $maxPlayers) = explode('-', $map['players'], 2);
            if ($map['nameId'] === 'factory4_night') continue;

            $progressBar->advance();
            $mapEntity = $mapRepository->findOneBy(['apiId' => $map['id']]);

            if ($mapEntity instanceof Map) {
                $mapEntity->setDefaultLocale($lang);
                $mapEntity->setName($map['name']);
                $mapEntity->setDescription($map['description']);
            } else {
                /** @var MapInterface $mapEntity */
                $mapEntity = new Map($lang);
                $mapEntity->setDefaultLocale($lang);
                /** TranslationInterface */
                $mapEntity->setName($map['name']);
                $mapEntity->setDescription($map['description']);
            }
            $duration = (new DateTime())->setTimestamp((int)$map['raidDuration']*60);
            $mapEntity
                ->setApiId($map['id'])
                ->setPublished(true)
                ->setSlug($map['normalizedName'])
                ->setRaidDuration($duration)
                ->setMinPlayersNumber((int)$minPlayers)
                ->setMaxPlayersNumber((int)$maxPlayers)
            ;
            $this->em->persist($mapEntity);
            $mapEntity->mergeNewTranslations();

            // todo bosses an zone spawn
        }
        $this->em->flush();
        $progressBar->finish();
        $io->success('Maps imported.');

        return Command::SUCCESS;
    }
}
