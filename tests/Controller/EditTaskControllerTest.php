<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 10:51
 */

namespace App\Tests\Controller;


use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class EditTaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected $em;
    protected $taskInBdd;
    protected $client = null;

    protected function dataFixture()
    {
        if ($this->taskInBdd == null) {
            $this->loadFixtures([
                AppFixtures::class,
            ]);

            $this->em = self::$container->get('doctrine')->getManager();
            $this->taskInBdd = $this->em->getRepository(Task::class)->findOneBy(['title' => 'title_test_one']);
        }
    }

    public function testEditAction()
    {
        $this->client = static::createClient();
        $this->dataFixture();
        $this->logIn();

        $crawler = $this->client->request('GET', 'tasks/'.$this->taskInBdd->getId().'/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Modifier',$crawler->filter('button')->text());

        //task modification form
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'title_test_one';
        $form['task[content]'] = 'content test one';
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();
        $this->assertSame(1, $crawler->filter('.alert-success')->count());
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