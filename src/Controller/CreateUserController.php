<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 02/05/2020
 * Time: 10:15
 */

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

class CreateUserController
{
    /**
     * @Route("/users/create", name="user_create")
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $em
     * @param Session $session
     * @param UrlGeneratorInterface $generator
     * @param Environment $twig
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createAction(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface  $em,
                                 Session $session,  UrlGeneratorInterface $generator, Environment $twig,
                                 UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
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

        return new Response($render);
    }
}