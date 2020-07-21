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
    public $em;
    public $taskInBdd;

    protected function doSetUp()
    {
        if ($this->client == null) {

            $this->client = static::createClient();
        }

        if ($this->taskInBdd == null)
        {
            $this->em = self::$container->get('doctrine')->getManager();
            $this->taskInBdd = $this->em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);
        }

        return $this->client;
    }

    public function testToggleTaskAction()
    {
        $this->doSetUp();
        $this->logIn();

        $isDone = $this->taskInBdd->isDone();

        $this->doSetUp()->request('GET', '/tasks/'.$this->taskInBdd->getId().'/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertSame(!$isDone, $this->taskInBdd->isDone());
    }

    /**
     * allows connection to the application
     */
    public function logIn()
    {
        $session = self::$container->get('session');

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'username_test']);

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