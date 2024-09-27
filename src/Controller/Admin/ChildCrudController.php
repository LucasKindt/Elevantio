<?php

namespace App\Controller\Admin;

use App\Entity\Child;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ChildCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Child::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Kind')
            ->setPageTitle('new', 'Kind toevoegen')
            ->setPageTitle('edit', 'Kind aanpassen')
            ->setPageTitle('detail', 'Kind')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Nieuwe kind');
            })
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
            TextField::new('age', 'Leeftijd'),
            TextField::new('class', 'Klas'),
            AssociationField::new('parent', 'Ouder'),
        ];
    }
}
