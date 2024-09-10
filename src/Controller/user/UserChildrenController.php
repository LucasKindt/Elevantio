<?php

namespace App\Controller\user;

use App\Form\UserChildrenType;
use App\Repository\SponsorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserChildrenController extends AbstractController
{
    #[Route('/gebruiker/kinderen', name: 'app_user_children')]
    public function index(
        sponsorRepository $sponsorRepository,
        Request $request,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $form = $this->createForm(UserChildrenType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_children');
        }

        return $this->render('user/children.html.twig', [
            'form' => $form,
            'user' => $user,
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }
}
