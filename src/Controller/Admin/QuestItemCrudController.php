<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Quest\QuestItem;
use App\Form\Field\TranslationField;
use App\Form\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class QuestItemCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuestItem::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)->setSearchFields([
            'translations.name',
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $published = BooleanField::new('published', t('Published', [], 'admin.quests_items'));
        $name = TextField::new('name', t('Name', [], 'admin.quests_items'));
        $shortName = TextField::new('shortName', t('Short name', [], 'admin.quests_items'));
        $questItemImage = VichImageField::new('imageFile', t('Photo', [], 'admin.quests_items')->getMessage())
            ->setTemplatePath('admin/field/vich_image.html.twig')
            ->setCustomOption('base_path', $this->getParameter('app.quests_items.images.uri'))
            ->setFormTypeOption('required', false)
        ;
        $width = IntegerField::new('width', t('Width', [], 'admin.quests_items'));
        $height = IntegerField::new('height', t('Height', [], 'admin.quests_items'));
        $translationFields = [
            'name' => [
                'field_type' => TextType::class,
                'label' => t('Name', [], 'admin.quests_items')
            ],
            'shortName' => [
                'field_type' => TextType::class,
                'label' => t('Short name', [], 'admin.quests_items')
            ],
            'description' => [
                'attr' => [
                    'class' => 'ckeditor'
                ],
                'field_type' => CKEditorType::class,
                'label' => t('Description', [], 'admin.quests_items')
            ],
        ];
        $translations = TranslationField::new('translations', t('Localization', [], 'admin.quests_items'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;
        $slug = SlugField::new('slug', t('Slug', [], 'admin.quests_items'))
            ->setTargetFieldName('slug')
            ->setRequired(true);
        $createdAt = DateField::new('createdAt', 'Created')->setTextAlign('center');
        $updatedAt = DateField::new('updatedAt', 'Updated')->setTextAlign('center');

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                $questItemImage,
                $published,
                $slug->setColumns(4),
                $width->setColumns(4),
                $height->setColumns(4),
                $translations,
            ],
            default => [
                $name->setTemplatePath('admin/field/link-edit.html.twig'),
                $shortName,
                $published,
                $createdAt,
                $updatedAt
            ]
        };
    }
}
