<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 04/05/2020
 * Time: 11:58
 */

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class ListTaskControllerTest extends WebTestCase
{
    protected $client = null;

    protected function doSetUp()
    {
        if ($this->client == null) {

            $this->client = static::createClient();
        }

        return $this->client;
    }

    public function testListTaskNoLoggedIn()
    {
        $this->doSetUp();
        $this->logIn();

        $crawler = $this->doSetUp()->request('GET', '/tasks');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1,$crawler->filter('.tasks')->count());
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