<?php

use \App\Entity\Task;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use \App\Entity\User;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    protected $user;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Given there is an admin username :username with password :password
     * @param $username
     * @param $password
     */
    public function thereIsAnAdminUserWithPassword($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setEmail('testemail@gmail.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $this->user = $user;
    }

    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $this->thereIsAnAdminUserWithPassword('admin', 'admin');

        $this->visitPath('/login');
        $this->fillField('username', 'admin');
        $this->fillField('password', 'admin');
        $this->pressButton('button');
        $this->assertPageContainsText('Créer une nouvelle tâche');
    }

    /**
     * @When I click :linkName
     * @param $linkName
     */
    public function iClick($linkName)
    {
        $this->clickLink($linkName);
    }

    /**
     * @Given there is an exiting user on behalf of :username
     * @param $username
     */
    public function thereIsAnExitingUserOnBehalfOf($username)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($this->encoder->encodePassword($user, 1));
        $user->setEmail('testemailtwo@gmail.com');
        $user->setRoles(array('ROLE_USER'));
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @When want to modify account :usernameModify
     * @param $usernameModify
     */
    public function wantToModifyAccount($usernameModify)
    {
        $user = $this->getContainer()->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => $usernameModify]);

        $this->iClick('edit_'.$user->getId());
    }

    /**
     * @Given there existing task on behalf of :titleTask
     * @param $titleTask
     */
    public function thereExistingTaskOnBehalfOf($titleTask)
    {
        $task = new Task();
        $task->setTitle($titleTask);
        $task->setContent('content of test');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();
    }

    /**
     * @When want delete the task :taskModify
     * @param $taskModify
     */
    public function wantDeleteTheTask($taskModify)
    {
        $task = $this->getContainer()->get('doctrine')
            ->getRepository(Task::class)
            ->findOneBy(['title' => $taskModify]);

        $this->pressButton('delete_'.$task->getId());
    }

    /**
     * @When I want toggle the task :taskToggle
     * @param $taskToggle
     */
    public function iWantToggleTheTask($taskToggle)
    {
        $task = $this->getContainer()->get('doctrine')
            ->getRepository(Task::class)
            ->findOneBy(['title' => $taskToggle]);

        $this->pressButton('toggle_'.$task->getId());
    }


    /**
     * @BeforeSuite
     */
    public static function beforeSuite()
    {
        StaticDriver::setKeepStaticConnections(true);
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        StaticDriver::beginTransaction();
        $this->user = null;
    }

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        StaticDriver::rollBack();
    }

    /**
     * @AfterSuite
     */
    public static function afterSuite()
    {
        StaticDriver::setKeepStaticConnections(false);
    }
}
