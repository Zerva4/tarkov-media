<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Item\ItemMaterial;
use App\Form\Field\TranslationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use function Symfony\Component\Translation\t;

class ItemMaterialCrudController extends BaseCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItemMaterial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)->setSearchFields([
            'translations.name',
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        $published = BooleanField::new('published', t('Published', [], 'admin'));
        $createdAt = DateField::new('createdAt', t('Created', [], 'admin'));
        $updatedAt = DateField::new('updatedAt', t('Updated', [], 'admin'));
        $name = TextField::new('name', t('Name', [], 'admin.items_materials'));
        $destructibility = NumberField::new('destructibility', t('Destructibility', [], 'admin.items_materials'));
        $minRepairDegradation = NumberField::new('minRepairDegradation', t('Min Repair Degradation', [], 'admin.items_materials'));
        $maxRepairDegradation = NumberField::new('maxRepairDegradation', t('Max Repair Degradation', [], 'admin.items_materials'));
        $explosionDestructibility = NumberField::new('explosionDestructibility', t('Explosion Destructibility', [], 'admin.items_materials'));
        $minRepairKitDegradation = NumberField::new('minRepairKitDegradation', t('Min Repair Kit Degradation', [], 'admin.items_materials'));
        $maxRepairKitDegradation = NumberField::new('maxRepairKitDegradation', t('Max Repair Kit Degradation', [], 'admin.items_materials'));
        $translationFields = [
            'name' => [
                'field_type' => TextType::class,
                'label' => t('Name', [], 'admin.items_materials'),
            ]
        ];
        $translations = TranslationField::new('translations', t('Localization', [], 'admin'), $translationFields)
            ->setFormTypeOptions([
                'excluded_fields' => ['lang', 'createdAt', 'updatedAt']
            ])
        ;

        return match ($pageName) {
            Crud::PAGE_EDIT, Crud::PAGE_NEW => [
                $published,
                $translations,
                $destructibility->setRequired(true)->setColumns(4),
                $minRepairDegradation->setRequired(true)->setColumns(4),
                $maxRepairDegradation->setRequired(true)->setColumns(4),
                $explosionDestructibility->setRequired(true)->setColumns(4),
                $minRepairKitDegradation->setRequired(true)->setColumns(4),
                $maxRepairKitDegradation->setRequired(true)->setColumns(4)
            ],
            default => [
                $name->setColumns(12)->setTextAlign('left')
                    ->setTemplatePath('admin/field/link-edit.html.twig'),
                $published,
                $createdAt,
                $updatedAt
            ],
        };
    }
}
