<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 04/05/2020
 * Time: 17:51
 */

namespace App\Tests\Entity;


use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    /**
     *Task entity test file. Tested with phpunit "./vendor/bin/simple-phpunit"
     */
    public function testTaskEntity()
    {
        $task = new Task();

        $task->setTitle('test');
        $task->setContent('content of test');
        $task->toggle(!$task->isDone());

        $dateTime = new \Datetime();
        $task->setCreatedAt($dateTime);

        $this->assertEquals(null, $task->getId());
        $this->assertEquals('test', $task->getTitle());
        $this->assertEquals('content of test', $task->getContent());
        $this->assertEquals(true, $task->isDone());
        $this->assertEquals($dateTime, $task->getCreatedAt());
    }
}