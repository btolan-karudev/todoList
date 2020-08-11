<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 02/05/2020
 * Time: 19:14
 */

namespace App\Controller;


use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ToggleTaskController
{
    /**
     * allows the ticking of "finished" or "to do" tasks.
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
}