<?php

namespace App\Controller\Admin;

use App\Entity\TargetGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TargetGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TargetGroup::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Doelgroepen')
            ->setPageTitle('new', 'Doelgroep aanmaken')
            ->setPageTitle('edit', 'Doelgroep bewerken')
            ->setPageTitle('detail', 'Doelgroep')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Nieuwe doelgroep');
            })
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Naam'),
        ];
    }
}
