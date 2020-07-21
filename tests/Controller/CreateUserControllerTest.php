<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 10/06/2020
 * Time: 16:05
 */

namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class CreateUserControllerTest extends WebTestCase
{
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
        $this->logIn();

        $crawler = $this->doSetUp()->request('GET', '/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Ajouter',$crawler->filter('button')->text());

        //allows to submit page form
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'username_test_two';
        $form['user[password][first]'] = '1';
        $form['user[password][second]'] = '1';
        $form['user[email]'] = 'username_test_email@gmail.com';
        $form['user[roles]'] = ['ROLE_USER'];
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