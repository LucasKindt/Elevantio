<?php

namespace App\Controller\Admin;

use App\Entity\Child;
use App\Entity\School;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'School')
            ->setPageTitle('new', 'School toevoegen')
            ->setPageTitle('edit', 'School aanpassen')
            ->setPageTitle('detail', 'School')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
        ];
    }
}
