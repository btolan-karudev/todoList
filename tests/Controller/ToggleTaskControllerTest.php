<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 18:42
 */

namespace App\Tests\Controller;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class ToggleTaskControllerTest extends WebTestCase
{
    protected $client = null;

    protected function doSetUp()
    {
        if ($this->client == null) {

            $this->client = static::createClient();
        }

        return $this->client;
    }

    public function testToggleTaskAction()
    {
        $this->doSetUp();
        $this->logIn();

        $em = self::$container->get('doctrine')->getManager();
        $task = $em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);
        $isDone = $task->isDone();

        $this->doSetUp()->request('GET', '/tasks/1/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame(!$isDone, $task->isDone());
    }

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