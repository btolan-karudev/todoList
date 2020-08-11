<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 22/07/2020
 * Time: 15:01
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserFixture extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
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

        $manager->flush();
    }
}