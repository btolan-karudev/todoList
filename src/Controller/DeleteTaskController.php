<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteTaskController
{
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
