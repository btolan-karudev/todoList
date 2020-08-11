<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 02/05/2020
 * Time: 19:09
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
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class CreateTaskController
{
    /**
     * allows the display of the task creation form and its recording in database
     * @Route("/tasks/create", name="task_create")
     * @param Request $request
     * @param FormFactoryInterface $formFactory
     * @param Environment $twig
     * @param UrlGeneratorInterface $generator
     * @param Session $session
     * @param EntityManagerInterface $em
     * @param Security $security
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createAction(Request $request, FormFactoryInterface $formFactory, Environment $twig,
                                 UrlGeneratorInterface $generator, Session $session, EntityManagerInterface  $em,
                                 Security $security)
    {
        $task = new Task();
        $form = $formFactory->create(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $task->setUser($security->getUser());
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
}