<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 12/06/2020
 * Time: 22:05
 */

namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoggedIn()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertSame('Nom d\'utilisateur :', $crawler->filter('label')->text());
    }
}