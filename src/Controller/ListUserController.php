<?php
/**
 * Created by PhpStorm.
 * User: michaelgt
 * Date: 02/05/2020
 * Time: 10:12
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ListUserController
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
}
