<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 06/05/2020
 * Time: 11:29
 */

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class CreateTaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected $client = null;

    protected function doSetUp()
    {
        if ($this->client == null) {

            $this->client = static::createClient();
        }



        return $this->client;
    }

    public function testCreateAction()
    {
        $this->doSetUp();
        $this->loadFixtures([
            AppFixtures::class,
        ]);

        $this->logIn();

        $user = new User();
        $mock = $this->createMock('Symfony\Component\Security\Core\Security');
        $mock->method('getUser')
            ->willReturn($user);

        $crawler = $this->doSetUp()->request('GET', '/tasks/create');

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