<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DefaultController
{
    /**
     * home page of logged in users
     * @Route("/", name="homepage")
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function indexAction(Environment $twig)
    {
        $render = $twig->render('default/index.html.twig');

        return new Response($render);
    }
}
