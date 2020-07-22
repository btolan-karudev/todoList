<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 10/06/2020
 * Time: 16:05
 */

namespace App\Tests\Controller;


use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class CreateUserControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected function dataFixture()
    {
        $this->loadFixtures([
            AppFixtures::class,
        ]);
    }

    public function testCreateAction()
    {
        $this->dataFixture();
        $this->logIn();

        $crawler = $this->client->request('GET', '/users/create');

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

        $firewallContext = 'main';

        $token = new PostAuthenticationGuardToken($user, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}