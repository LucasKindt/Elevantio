<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Child;
use App\Entity\School;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Beheer');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Activiteiten', 'fa fa-tasks', Activity::class);
        yield MenuItem::linkToCrud('Scholen', 'fa fa-school', School::class);
        yield MenuItem::linkToCrud('Gebruikers', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Categorieën', 'fa fa-user', Category::class);
        yield MenuItem::linkToCrud('Sponsoren', 'fa fa-heart', Sponsor::class);
        yield MenuItem::linkToCrud('Doelgroepen', 'fa fa-user', TargetGroup::class);
        yield MenuItem::linkToCrud('Kinderen', 'fa fa-child', Child::class);
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
            ->displayUserName(false)

            // you can also pass an email address to use gravatar's service
            //->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
            ]);
    }
}
