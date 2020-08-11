<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 04/05/2020
 * Time: 13:12
 */

namespace App\Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     *Task entity User file. Tested with phpunit "./vendor/bin/simple-phpunit"
     */
    public function testUserEntity()
    {
        $user = new User();

        $user->setUsername('username test');
        $user->setPassword('ds2535dfdgf43ff353sdfs2f');
        $user->setEmail('email_test@gmail.com');

        $this->assertEquals(null, $user->getId());
        $this->assertEquals('username test', $user->getUsername());
        $this->assertEquals('ds2535dfdgf43ff353sdfs2f', $user->getPassword());
        $this->assertEquals('email_test@gmail.com', $user->getEmail());
        $this->assertArrayHasKey(0 , $user->getRoles(), 'ROLE_USER');

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertArrayHasKey(0 , $user->getRoles(), 'ROLE_ADMIN');

        $user->eraseCredentials();

        $tasks = new Task();
        $user->addTask($tasks);

        $this->assertObjectHasAttribute('title', $user->getTasks()->get(0));
        $user->removeTask($tasks);
    }
}