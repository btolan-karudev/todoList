<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 07/07/2020
 * Time: 06:31
 */

namespace App\Tests\Security;

use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticatorTest extends TestCase
{

    use TargetPathTrait;

    public $em;
    public $urlGenerator;
    public $token;
    public $userPassword;

    public function mock()
    {
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlGenerator = $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->token = $this->getMockBuilder('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->userPassword = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetCredentials()
    {
        $this->mock();

        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $request->request->set('username', 'usernameTest');
        $request->request->set('password', '1');
        $request->request->set('_csrf_token', 'testToken');

        $LoginFormAuthenticator = new LoginFormAuthenticator($this->em, $this->urlGenerator, $this->token, $this->userPassword);

        $credential = $LoginFormAuthenticator->getCredentials($request);

        $this->assertEquals('usernameTest', $credential['username']);
    }

    /**
     * the user exist in the database
     */
    public function testGetUserExist()
    {
        $this->mock();

        $userProvider = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserProviderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createMock('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
        $mock->method('isTokenValid')
            ->willReturn(true);

        $dataUser = [
            'username' => 'username_test',
            ];


        $data = [
            'username' => 'username_test',
            'password' => '1',
            'csrf_token' => 'testToken',
        ];

        $userMock = $this->createMock(ObjectRepository::class);
        $userMock->expects($this->any())
            ->method('findOneBy')
            ->willReturn($dataUser);

        $entity = $this->createMock(EntityManagerInterface::class);
        $entity->expects($this->any())
            ->method('getRepository')
            ->willReturn($userMock);

        $loginFormAuthenticator = new LoginFormAuthenticator($entity, $this->urlGenerator, $mock, $this->userPassword);

        $userLogedIn = $loginFormAuthenticator->getuser($data, $userProvider);

        $this->assertEquals($userLogedIn['username'], $data['username']);

    }

    public function testCheckCredentials()
    {
        $this->mock();

        $data = [
            'username' => 'username_test',
            'password' => '1',
            'csrf_token' => 'testToken',
        ];

        $userInterface = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createMock(UserPasswordEncoderInterface::class);
        $mock->method('isPasswordValid')
            ->willReturn(true);

        $loginFormAuthenticator = new LoginFormAuthenticator($this->em, $this->urlGenerator, $this->token, $mock);

        $resultCheckPassword = $loginFormAuthenticator->checkCredentials($data, $userInterface);

        $this->assertEquals(true, $resultCheckPassword);

    }

    public function testOnAuthenticationSuccess()
    {
        $this->mock();

        $generate = $this->createMock(UrlGeneratorInterface::class);
        $generate->expects($this->any())
            ->method('generate')
            ->willReturn('/login');

        $loginFormAuthenticator = new LoginFormAuthenticator($this->em, $generate, $this->token, $this->userPassword);

        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $tokenInterface = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $providerKey = "null";

        $redirectResponse = $loginFormAuthenticator->onAuthenticationSuccess($request, $tokenInterface, $providerKey);

        $this->assertEquals('302', $redirectResponse->getStatusCode());
    }

    /** test of exceptions */

    public function testGetUserTokenException()
    {
        $this->mock();

        $userProvider = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserProviderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createMock('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
        $mock->method('isTokenValid')
            ->willReturn(false);

        $dataUser = [
            'username' => 'username_test',
        ];


        $data = [
            'username' => 'username_test',
            'password' => '1',
            'csrf_token' => 'testToken',
        ];

        $userMock = $this->createMock(ObjectRepository::class);
        $userMock->expects($this->any())
            ->method('findOneBy')
            ->willReturn($dataUser);

        $entity = $this->createMock(EntityManagerInterface::class);
        $entity->expects($this->any())
            ->method('getRepository')
            ->willReturn($userMock);

        $loginFormAuthenticator = new LoginFormAuthenticator($entity, $this->urlGenerator, $mock, $this->userPassword);

        $this->expectException(InvalidCsrfTokenException::class);
        $loginFormAuthenticator->getuser($data, $userProvider);
    }

    public function testGetUserExceptionNoUserInTheBdd()
    {
        $this->mock();

        $userProvider = $this->getMockBuilder('Symfony\Component\Security\Core\User\UserProviderInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createMock('Symfony\Component\Security\Csrf\CsrfTokenManagerInterface');
        $mock->method('isTokenValid')
            ->willReturn(true);

        $dataUser = null;


        $data = [
            'username' => 'username_test',
            'password' => '1',
            'csrf_token' => 'testToken',
        ];

        $userMock = $this->createMock(ObjectRepository::class);
        $userMock->expects($this->any())
            ->method('findOneBy')
            ->willReturn($dataUser);

        $entity = $this->createMock(EntityManagerInterface::class);
        $entity->expects($this->any())
            ->method('getRepository')
            ->willReturn($userMock);

        $loginFormAuthenticator = new LoginFormAuthenticator($entity, $this->urlGenerator, $mock, $this->userPassword);

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $loginFormAuthenticator->getuser($data, $userProvider);
    }

    public function testCheckCredentialsException()
    {
        $this->mock();

        $data = [
            'username' => 'username_test',
            'password' => '1',
            'csrf_token' => 'testToken',
        ];

        $userInterface = $this->getMockBuilder(UserInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock = $this->createMock(UserPasswordEncoderInterface::class);
        $mock->method('isPasswordValid')
            ->willReturn(false);

        $loginFormAuthenticator = new LoginFormAuthenticator($this->em, $this->urlGenerator, $this->token, $mock);

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $loginFormAuthenticator->checkCredentials($data, $userInterface);
    }

    public function testOnAuthenticationSuccessRedirectionPath()
    {
        $this->mock();

        $generate = $this->createMock(UrlGeneratorInterface::class);
        $generate->expects($this->any())
            ->method('generate')
            ->willReturn('/login');

        $loginFormAuthenticator = new LoginFormAuthenticator($this->em, $generate, $this->token, $this->userPassword);

        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);

        $tokenInterface = $this->getMockBuilder(TokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $providerKey = "login";

        $this->saveTargetPath($request->getSession(), $providerKey, '/login');

        $redirectResponse = $loginFormAuthenticator->onAuthenticationSuccess($request, $tokenInterface, $providerKey);

        $this->assertEquals('302', $redirectResponse->getStatusCode());
    }
}