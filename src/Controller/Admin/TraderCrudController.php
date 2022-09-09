<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Trader;
use App\Form\Field\TranslationField;
use App\Form\Field\VichImageField;
use App\Form\TraderLevelForm;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class TraderCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trader::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $published = BooleanField::new('published', t('Published', [], 'admin.traders'));
        $fullName = TextField::new('fullName', t('Full name', [], 'admin.traders'));
        $characterType = TextField::new('characterType', t('Character type', [], 'admin.traders'));
        $slug = TextField::new('slug', t('Slug', [], 'admin.traders'));
        $avatar = VichImageField::new('imageFile', t('Photo', [], 'admin.locations')->getMessage())
            ->setTemplatePath('admin/field/vich_image.html.twig')
            ->setCustomOption('base_path', $this->getParameter('app.traders.images.uri'))
            ->setFormTypeOption('required', false);
        ;
        $createdAt = DateField::new('createdAt', 'Created');
        $updatedAt = DateField::new('updatedAt', 'Updated');

        $translationFields = [
            'characterType' => [
                'field_type' => TextType::class,
                'label' => t('Character type', [], 'admin.traders'),
            ],
            'fullName' => [
                'field_type' => TextType::class,
                'label' => t('Full name', [], 'admin.traders'),
            ],
            'description' => [
                'attr' => [
                    'class' => 'ckeditor'
                ],
                'field_type' => CKEditorType::class,
                'label' => t('Description', [], 'admin.traders')
            ],
        ];
        $translations = TranslationField::new('translations', t('Localization', [], 'admin.locations'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;

        $levels = CollectionField::new('levels', t('Trader levels', [], 'admin.traders'))
            ->allowAdd()
            ->allowDelete()
            ->setEntryType(TraderLevelForm::class)
            ->setEntryIsComplex(false)
            ->setFormTypeOption('by_reference', false)
        ;

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                FormField::addTab(t('Basic', [], 'admin.traders')),
                $avatar,
                $published,
                $slug->setColumns(6)->setTextAlign('left'),
                $translations,
                FormField::addTab(t('Levels', [], 'admin.traders')),
                $levels->setColumns(12)
            ],
            default => [$characterType, $fullName, $published, $createdAt, $updatedAt],
        };
    }
}
