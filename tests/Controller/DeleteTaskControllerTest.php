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
    public $em;
    public $taskInBddnoDeleted;
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
            $this->taskInBddnoDeleted = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task_of_test']);
        }

        return $this->client;
    }

    public function testDeleteTaskAction()
    {
        $this->doSetUp();
        $this->logIn();


        $this->doSetUp()->request('GET', '/tasks/'.$this->taskInBdd->getId().'/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('.alert-success')->count());

        $taskVerifDeleteBdd = $this->em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);

        $this->assertSame(null, $taskVerifDeleteBdd);
    }

    public function testDeleteTaskActionNoAuthorized()
    {
        $this->doSetUp();
        $this->logIn();

        $this->doSetUp()->request('GET', '/tasks/'.$this->taskInBddnoDeleted->getId().'/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('.alert-danger')->count());
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