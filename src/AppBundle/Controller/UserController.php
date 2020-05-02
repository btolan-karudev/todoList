<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class UserController
{
    /**
     * @Route("/users", name="user_list")
     * @param Environment $twig
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listAction(Environment $twig, EntityManagerInterface  $em)
    {
        $render= $twig->render('user/list.html.twig', [
            'users' => $em->getRepository(User::class)->findAll()
        ]);

        return new Response($render);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param UrlGeneratorInterface $generator
     * @param Environment $twig
     * @return RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createAction(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface  $em,
                                 Session $session,  UrlGeneratorInterface $generator, Environment $twig)
    {
        $user = new User();
        $form = $formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', "L'utilisateur a bien été ajouté.");

            $router = $generator->generate('user_list');

            return new RedirectResponse($router, 302);
        }

        $render = $twig->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);

        return new RedirectResponse($render);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @param User $user
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param UrlGeneratorInterface $generator
     * @return RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editAction(User $user, Request $request, FormFactoryInterface $formFactory, Environment $twig,
                               EntityManagerInterface  $em, Session $session, UrlGeneratorInterface $generator)
    {
        $form = $formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
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

        return new RedirectResponse($render);
    }
}
