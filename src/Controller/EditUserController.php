<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class EditUserController
{
    /**
     * allows the modification of an account user
     * @Route("/users/{id}/edit", name="user_edit")
     * @param User $user
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param UrlGeneratorInterface $generator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editAction(User $user, Request $request, FormFactoryInterface $formFactory, Environment $twig,
                               EntityManagerInterface  $em, Session $session, UrlGeneratorInterface $generator,
                               UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->flush();

            $session->getFlashBag()->add('success', "L'utilisateur a bien été modifié");

            $router = $generator->generate('user_list');
            return new RedirectResponse($router, 302);
        }

        $render = $twig->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);

        return new Response($render);
    }
}
