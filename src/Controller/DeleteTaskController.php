<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class DeleteTaskController
{
    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @param Task $task
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @param Security $security
     * @return RedirectResponse
     */
    public function deleteTaskAction(Task $task, UrlGeneratorInterface $generator, Session $session,
                                     EntityManagerInterface  $em, Security $security)
    {
        if (($security->getUser() == $task->getUser()) ||
            ($security->isGranted('ROLE_ADMIN') && $task->getUser() == null)) {

            $em->remove($task);
            $em->flush();
            $session->getFlashBag()->add('success', 'La tâche a bien été supprimée.');

        } else {
            $session->getFlashBag()->add('error', 'Désolé mais vous ne pouvez pas supprimer la tache.');
        }

        $router = $generator->generate('task_list');

        return new RedirectResponse($router, 302);
    }
}