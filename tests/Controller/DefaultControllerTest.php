<?php

namespace App\Tests\Controller;

use App\DataFixtures\AdminUserFixture;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class DefaultControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function dataFixture()
    {
        $this->loadFixtures([
            AdminUserFixture::class,
        ]);
    }

    public function testIndexActionUserNotLoggedIn()
    {
        $this->client->request('GET', '/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexActionUserLoggedIn()
    {
        $this->dataFixture();
        $this->logIn();

        $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
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
        $this->client->getCookieJar()->set($cookie);
    }
}
