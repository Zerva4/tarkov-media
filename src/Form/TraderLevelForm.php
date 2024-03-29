<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Trader\TraderLevel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\Translation\t;

class TraderLevelForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('level', IntegerType::class, [
                'label' => t('Level', [], 'admin.traders')
            ])
            ->add('requiredPlayerLevel', IntegerType::class, [
                'label' => t('Required player level', [], 'admin.traders')
            ])
            ->add('requiredReputation', NumberType::class, [
                'label' => t('Required reputation', [], 'admin.traders')
            ])
            ->add('requiredSales', IntegerType::class, [
                'label' => t('Required sales', [], 'admin.traders')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TraderLevel::class,
        ]);
    }
}
