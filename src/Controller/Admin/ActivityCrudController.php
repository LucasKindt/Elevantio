<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Form\ActivityDateType;
use App\Repository\ActivityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\QueryBuilder;

class ActivityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Activity::class;
    }

    public function createEntity(string $entityFqcn): Activity
    {
        $activity = new Activity();
        $activity->setCreator($this->getUser());

        return $activity;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Activiteit')
            ->setPageTitle('new', 'Activiteit aanmaken')
            ->setPageTitle('edit', 'Activiteit bewerken')
            ->setPageTitle('detail', 'Activiteit')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Nieuwe activiteit');
            })
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
            TextareaField::new('description', 'Beschrijving')->hideOnIndex(),
            TextField::new('location', 'Locatie'),
            AssociationField::new('school', 'School')->autocomplete(),
            AssociationField::new('targetGroup', 'Doelgroep')->autocomplete(),
            AssociationField::new('category', 'Categorie')->autocomplete(),
            CollectionField::new('activityDates', 'Datums')->setEntryType(ActivityDateType::class)->setTemplatePath('admin/fields/activitydates.html.twig')->hideOnIndex(),
            ImageField::new('image', 'Afbeelding')
                ->setBasePath('uploads/images/')
                ->setUploadDir('public/uploads/images/')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired($pageName !== Crud::PAGE_EDIT)
                ->setFormTypeOptions($pageName == Crud::PAGE_EDIT ? ['allow_delete' => false] : []),
            MoneyField::new('price', 'Prijs')->setCurrency('EUR'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $userRoles = $this->getUser()->getRoles();

        // Check if the user has the 'ROLE_SUPPLIER'
        if(in_array('ROLE_ADMIN', $userRoles))
        {
            $queryBuilder->where('entity.id IS NOT NULL');
        }
        elseif (in_array('ROLE_SUPPLIER', $userRoles)) {
            $queryBuilder->where('entity.creator = :user')
                ->setParameter('user', $this->getUser()->getId());
        }


        return $queryBuilder;
    }
}