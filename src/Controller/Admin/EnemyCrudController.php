<?php

namespace App\Controller\Admin;

use App\Entity\Enemy;
use App\Form\Field\TranslationField;
use App\Form\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class EnemyCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Enemy::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $published = BooleanField::new('published', t('Published', [], 'admin.enemies'));
        $createdAt = DateField::new('createdAt', 'Created');
        $updatedAt = DateField::new('updatedAt', 'Updated');
        $name = TextField::new('name', t('Name', [], 'admin.enemies'));
        $slug = TextField::new('slug', t('Slug', [], 'admin.enemies'));
        $image = VichImageField::new('imageFile', t('Photo', [], 'admin.enemies')->getMessage())
            ->setTemplatePath('admin/field/vich_image.html.twig')
            ->setCustomOption('base_path', $this->getParameter('app.enemies.images.uri'))
            ->setFormTypeOption('required', false);
        ;
        $types = ChoiceField::new('types', t('Type'))
            ->allowMultipleChoices()
            ->setTextAlign('left')
            ->setChoices([
                'TYPE_BOSS' => 'TYPE_BOSS',
                'ROLE_FOLLOWER' => 'ROLE_FOLLOWER',
            ])
            ->setTranslatableChoices([
                'TYPE_BOSS' => t('Boss', [], 'admin.enemies'),
                'ROLE_FOLLOWER' => t('Follower', [], 'admin.enemies'),
            ])
        ;
        $translationFields = [
            'name' => [
                'field_type' => TextType::class,
                'label' => t('Name', [], 'admin.bosses'),
            ],
            'behavior' => [
                'attr' => [
                    'class' => 'ckeditor'
                ],
                'field_type' => CKEditorType::class,
                'label' => t('Behavior', [], 'admin.bosses')
            ],
            'strategy' => [
                'attr' => [
                    'class' => 'ckeditor'
                ],
                'field_type' => CKEditorType::class,
                'label' => t('Strategy', [], 'admin.bosses')
            ],
            'followers' => [
                'attr' => [
                    'class' => 'ckeditor'
                ],
                'field_type' => CKEditorType::class,
                'label' => t('Followers', [], 'admin.bosses')
            ],
        ];
        $translations = TranslationField::new('translations', t('Localization', [], 'admin.bosses'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                $image,
                $published,
                $types,
                $slug,
                $translations,
            ],
            default => [$name, $published, $types, $createdAt, $updatedAt],
        };
    }
}
