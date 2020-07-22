<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 10/06/2020
 * Time: 19:43
 */

namespace App\Tests\Controller;


use App\DataFixtures\AdminUserFixture;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class EditUserControllerTest extends WebTestCase
{
    use FixturesTrait;

    protected $userInBdd;
    protected $em;

    protected function dataFixture()
    {
        if ($this->userInBdd == null) {
            $this->loadFixtures([
                AdminUserFixture::class,
            ]);

            $this->em = self::$container->get('doctrine')->getManager();
            $this->userInBdd = $this->em->getRepository(User::class)->findOneBy(['username' => 'username_test']);
        }

    }

    public function testEditAction()
    {
        $this->dataFixture();
        $this->logIn();

        $crawler = $this->client->request('GET', 'users/'.$this->userInBdd->getId().'/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Modifier',$crawler->filter('button')->text());

        //user modification form
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[password][first]'] = '1';
        $form['user[password][second]'] = '1';
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