<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 20:51
 */

namespace App\Tests\Controller;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class DeleteTaskControllerTest extends WebTestCase
{
    protected $client = null;

    protected function doSetUp()
    {
        if ($this->client == null) {

            $this->client = static::createClient();
        }

        return $this->client;
    }

    public function testDeleteTaskAction()
    {
        $this->doSetUp();
        $this->logIn();

        $task = new Task();
        $task->setTitle('title_test_for');
        $task->setContent('content test for');

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();

        $taskInBdd = $em->getRepository(Task::class)->findOneBy(['title' => 'title_test_for']);

        $this->doSetUp()->request('GET', '/tasks/'.$taskInBdd->getId().'/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $taskVerifDeleteBdd = $em->getRepository(Task::class)->findOneBy(['title' => 'title_test_for']);

        $this->assertSame(null, $taskVerifDeleteBdd);
    }

    /**
     * allows connection to the application
     */
    public function logIn()
    {
        $session = self::$container->get('session');

        $user = self::$container->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'username_test']);

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new PostAuthenticationGuardToken($user, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->doSetUp()->getCookieJar()->set($cookie);
    }
}