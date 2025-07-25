<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'user_edit')]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }
}
