<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 05/05/2020
 * Time: 10:42
 */

namespace App\Tests\Form;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class UserTypeTest extends TypeTestCase
{
    /**
     * Adding custom extensions
     * @return array
     */
    protected function getExtensions()
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    /**
     *UserType file test file. Tested with phpunit "./vendor/bin/simple-phpunit"
     */
    public function testBuildForm()
    {
        $data = [
            'username' => 'nameTest',
            'email' => 'test_email@gmial.com',
            'password' => [
                'first' => 'dsdqs5464dfds654fd65f446ds',
                'second' => 'dsdqs5464dfds654fd65f446ds'
            ],
        ];

        $user = new User();

        $form = $this->factory->create(UserType::class, $user);

        $userToCompare = new User();

        $userToCompare->setUsername($data['username']);
        $userToCompare->setPassword($data['password']['first']);
        $userToCompare->setEmail($data['email']);

        $form->submit($data);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($user->getUsername(), $userToCompare->getUsername());
        $this->assertEquals($user->getEmail(), $userToCompare->getEmail());
        $this->assertEquals($user->getPassword(), $userToCompare->getPassword());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}