<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 11:29
 */

namespace App\Tests\Controller;

use App\DataFixtures\AdminUserFixture;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class CreateTaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    public $fix;
    protected function dataFixture()
    {
        $this->loadFixtures([
            AdminUserFixture::class,
        ]);

        $this->fix = $this->client;
    }

    public function testCreateAction()
    {
        $this->dataFixture();
        $this->logIn();

        $user = new User();
        $mock = $this->createMock('Symfony\Component\Security\Core\Security');
        $mock->method('getUser')
            ->willReturn($user);

        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Ajouter',$crawler->filter('button')->text());

        //allows to submit page form
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'title_test_three';
        $form['task[content]'] = 'content test three';
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

        $user = self::$container->get('doctrine')->getRepository(User::class)->findOneBy(['username' => 'username_test']);

        $firewallName = 'main';

        $firewallContext = 'main';

        $token = new PostAuthenticationGuardToken($user, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->fix->getCookieJar()->set($cookie);
    }
}