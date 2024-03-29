<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Workshop\Place;
use App\Form\Field\TranslationField;
use App\Form\PlaceLevelFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class PlaceCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Place::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $translationFields = [
            'name' => [
                'field_type' => TextType::class,
                'label' => t('Name', [], 'admin.places')
            ],
        ];
        $translations = TranslationField::new('translations', t('Localization', [], 'admin'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;
        $name = TextField::new('name', t('Name', [], 'admin.places'));
        $order = IntegerField::new('orderPlace', t('Order', [], 'admin.places'));
        $slug = SlugField::new('slug', t('Slug', [], 'admin'))
            ->setTargetFieldName('slug')
            ->setRequired(true);
        $published = BooleanField::new('published', t('Published', [], 'admin'));
        $createdAt = DateField::new('createdAt', 'Created')->setTextAlign('center');
        $updatedAt = DateField::new('updatedAt', 'Updated')->setTextAlign('center');
        $levels = CollectionField::new('levels', t('Levels', [], 'admin.places'))
            ->allowAdd()
            ->allowDelete()
            ->setEntryType(PlaceLevelFormType::class)
            ->setEntryIsComplex(false)
            ->setFormTypeOption('by_reference', true)
            ->setRequired(false)
            ->setColumns(12)
        ;

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                FormField::addTab(t('Basic', [], 'admin.places')),
                $published,
                $translations->setColumns(12),
                $slug->setColumns(6),
                $order->setColumns(6),
                FormField::addTab(t('Levels', [], 'admin.places')),
                $levels
            ],
            default => [
                $name->setTextAlign('left')->setTemplatePath('admin/field/link-edit.html.twig'),
                $order->setTextAlign('center'),
                $published->setTextAlign('center'),
                $createdAt->setTextAlign('center'),
                $updatedAt->setTextAlign('center')
            ]
        };
    }
}
