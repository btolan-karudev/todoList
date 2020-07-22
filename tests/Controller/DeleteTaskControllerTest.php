<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 20:51
 */

namespace App\Tests\Controller;


use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class DeleteTaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected $em;
    protected $taskInBddNoDeleted;
    protected $taskInBdd;

    protected function dataFixture()
    {
        if ($this->taskInBdd == null) {
            $this->loadFixtures([
                AppFixtures::class,
            ]);

            $this->em = self::$container->get('doctrine')->getManager();
            $this->taskInBdd = $this->em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);
            $this->taskInBddNoDeleted = $this->em->getRepository(Task::class)->findOneBy(['title' => 'task_of_test']);
        }
    }

    public function testDeleteTaskAction()
    {
        $this->dataFixture();
        $this->logIn();

        $this->client->request('GET', '/tasks/'.$this->taskInBdd->getId().'/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('.alert-success')->count());

        $taskVerificationDeleteBdd = $this->em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);

        $this->assertSame(null, $taskVerificationDeleteBdd);
    }

    public function testDeleteTaskActionNoAuthorized()
    {
        $this->dataFixture();
        $this->logIn();

        $this->client->request('GET', '/tasks/'.$this->taskInBddNoDeleted->getId().'/delete');

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

        $firewallContext = 'main';

        $token = new PostAuthenticationGuardToken($user, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}