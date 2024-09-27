<?php

namespace App\Controller\Admin;

use App\Entity\Signup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SignupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Signup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Inschrijvingen')
            ->setPageTitle('new', 'Inschrijving aanmaken')
            ->setPageTitle('edit', 'Inschrijving bewerken')
            ->setPageTitle('detail', 'Inschrijving')
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setLabel('Nieuwe aanmelding');
            })
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('child', 'Kind'),
            AssociationField::new('activity', 'Activiteit'),
        ];
    }
}
