<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 02/05/2020
 * Time: 19:05
 */

namespace App\Controller;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ListTaskController
{
    /**
     * displays a page listing the tasks recorded in database
     * @Route("/tasks", name="task_list")
     * @param  EntityManagerInterface  $em
     * @param Environment $twig
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listAction(EntityManagerInterface  $em, Environment $twig)
    {
        $render = $twig->render('task/list.html.twig', [
            'tasks' => $em->getRepository(Task::class)->findAll()
        ]);

        return new Response($render);
    }
}