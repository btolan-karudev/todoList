<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $userOne = new User();
        $userOne->setUsername('username_test');
        $userOne->setPassword($this->encoder->encodePassword($userOne,'1'));
        $userOne->setEmail('test_email@gmail.com');
        $userOne->setRoles([
            "ROLE_USER",
            "ROLE_ADMIN"
        ]);
        $manager->persist($userOne);

        $userTwo = new User();
        $userTwo->setUsername('user_two');
        $userTwo->setPassword($this->encoder->encodePassword($userTwo,'1'));
        $userTwo->setEmail('userTwo@gmail.com');
        $userTwo->setRoles(["ROLE_USER"]);
        $manager->persist($userTwo);

        $taskOne = new Task();
        $taskOne->setTitle('title_test_one');
        $taskOne->setContent('content test one');
        $manager->persist($taskOne);

        $taskTwo = new Task();
        $taskTwo->setTitle('title_test_two');
        $taskTwo->setContent('content test two');
        $taskTwo->setUser($userOne);
        $manager->persist($taskTwo);

        $taskTree = new Task();
        $taskTree->setTitle('task_of_test');
        $taskTree->setContent('task of test');
        $taskTree->setUser($userTwo);
        $manager->persist($taskTree);

        $manager->flush();
    }
}
