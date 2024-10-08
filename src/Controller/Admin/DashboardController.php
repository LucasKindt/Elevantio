<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Child;
use App\Entity\School;
use App\Entity\Signup;
use App\Entity\Sponsor;
use App\Entity\TargetGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    #[Route('/beheer', name: 'app_dashboard')]
    public function index(): Response
    {
        // If isn't admin or supplier
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_SUPPLIER'))
        {
            return $this->render('admin/index.html.twig');
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Beheer');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::subMenu('Activiteiten', 'fa fa-tasks')
            ->setSubItems([
                MenuItem::linkToCrud('Beheren', 'fa fa-list', Activity::class)
                    ->setPermission('ROLE_ADMIN')->setPermission('ROLE_SUPPLIER'),
            ]);
        yield MenuItem::subMenu('Variabelen', 'fa fa-gear')
            ->setSubItems([
                MenuItem::linkToCrud('Categorieën', 'fa fa-list-alt', Category::class)
                    ->setPermission('ROLE_ADMIN'),
                MenuItem::linkToCrud('Scholen', 'fa fa-school', School::class)
                    ->setPermission('ROLE_ADMIN'),
                MenuItem::linkToCrud('Doelgroepen', 'fa fa-user', TargetGroup::class)
                    ->setPermission('ROLE_ADMIN'),
            ]);
        yield MenUitem::section('<hr>');
        yield MenuItem::linkToCrud('Gebruikers', 'fa fa-user', User::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Kinderen', 'fa fa-child', Child::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Sponsoren', 'fa fa-heart', Sponsor::class)
            ->setPermission('ROLE_ADMIN');

        //yield MenuItem::linkToCrud('Aanmeldingen', 'fa fa-child', Signup::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getEmail())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false);
    }
}
