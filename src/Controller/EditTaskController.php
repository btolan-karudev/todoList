<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 02/05/2020
 * Time: 19:12
 */

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EditTaskController
{
    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @param Task $task
     * @param Request $request
     * @param Environment $twig
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editAction(Task $task, Request $request, Environment $twig, FormFactoryInterface $formFactory,
                               UrlGeneratorInterface $generator, Session $session, EntityManagerInterface  $em)
    {
        $form = $form = $formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->flush();

            $session->getFlashBag()->add('success', 'La tâche a bien été modifiée.');

            $router = $generator->generate('task_list');
            return new RedirectResponse($router, 302);
        }

        $render= $twig->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);

        return new Response($render);
    }
}