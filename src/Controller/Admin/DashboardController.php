<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Child;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductCategory;
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
        // If admin or employee
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_EMPLOYEE'))
        {
            return $this->render('admin/index.html.twig');
        }
        else
        {
            return $this->redirectToRoute('app_login');
        }
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Kapsalon | Je haar zit goed!');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::LinktoCrud('Medewerkers', 'fa fa-user', User::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::LinktoCrud('Producten', 'fa fa-star', Product::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::LinktoCrud('Bestellingen', 'fa fa-shopping-cart', Order::class)
            ->setPermission('ROLE_EMPLOYEE');
        yield MenUitem::section('<hr>');
        yield MenuItem::LinktoCrud('Categorieën', 'fa fa-user', ProductCategory::class)
            ->setPermission('ROLE_ADMIN');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getEmail())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false);
    }
}
