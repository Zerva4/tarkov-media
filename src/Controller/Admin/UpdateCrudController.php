<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Update\Update;
use App\Form\Field\TranslationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class UpdateCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return Update::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)->setSearchFields([
            'translations.description']
        );
    }

    public function configureFields(string $pageName): iterable
    {
        $createdAt = DateField::new('createdAt', 'Created');
        $updatedAt = DateField::new('updatedAt', 'Updated');
        $description = TextField::new('description', t('Description', [], 'admin'));
        $category = AssociationField::new('category', t('Category', [], 'admin'))
            ->setQueryBuilder(function($queryBuilder) {
                return $queryBuilder->join('entity.translations', 'lt', 'WITH', 'entity.id = lt.translatable')
                    ->addSelect('lt')
                    ->andWhere('lt.locale = :locale')
                    ->setParameter('locale', $this->container->get('request_stack')->getCurrentRequest()->getLocale())
                    ;
            })
            ->setColumns(12)
        ;
        $translationFields = [
            'description' => [
                'field_type' => TextareaType::class,
                'label' => t('Description', [], 'admin'),
            ],
        ];

        $translations = TranslationField::new('translations', t('Localization', [], 'admin'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                $category,
                $translations,
            ],
            default => [
                $description->setTemplatePath('admin/field/link-edit.html.twig'),
                $category,
                $createdAt, $updatedAt],
        };
    }
}
