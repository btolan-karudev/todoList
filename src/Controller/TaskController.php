<?php

namespace App\Controller;

use App\Entity\Task;
use AppBundle\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class TaskController
{
    /**
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
        $render= $twig->render('task/list.html.twig', [
            'tasks' => $em->getRepository(Task::class)->findAll()
        ]);

        return new Response($render);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createAction(Request $request,FormFactoryInterface $formFactory, Environment $twig,
                                 UrlGeneratorInterface $generator, Session $session, EntityManagerInterface  $em)
    {
        $task = new Task();
        $form = $formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $em->persist($task);
            $em->flush();

            $session->getFlashBag()->add('success', 'La tâche a été bien été ajoutée.');

            $router = $generator->generate('task_list');
            return new RedirectResponse($router, 302);
        }

        $render= $twig->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);

        return new Response($render);
    }

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

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @param Task $task
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function toggleTaskAction(Task $task, UrlGeneratorInterface $generator, Session $session,
                                     EntityManagerInterface  $em)
    {
        $task->toggle(!$task->isDone());
        $em->flush();

        $session->getFlashBag()->add(
            'success',
            sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle())
        );

        $router = $generator->generate('task_list');

        return new RedirectResponse($router, 302);
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @param Task $task
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function deleteTaskAction(Task $task, UrlGeneratorInterface $generator, Session $session,
                                     EntityManagerInterface  $em)
    {
        $em->remove($task);
        $em->flush();

        $session->getFlashBag()->add('success', 'La tâche a bien été supprimée.');

        $router = $generator->generate('task_list');

        return new RedirectResponse($router, 302);
    }
}
