<?php
/**
 * Created by PhpStorm.
 * User: mickd
 * Date: 04/05/2020
 * Time: 18:40
 */

namespace App\Tests\Form;


use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    /**
     *TaskType file test file. Tested with phpunit "./vendor/bin/simple-phpunit"
     */
    public function testBuildForm()
    {
        $data = [
            'title' => 'test',
            'content' => 'content test',
        ];

        $task = new Task();

        $form =  $this->factory->create(TaskType::class, $task);

        $taskToCompare = new Task();
        $taskToCompare->setTitle($data['title']);
        $taskToCompare->setContent($data['content']);

        $form->submit($data);

        $this->assertTrue($form->isSynchronized());


        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }        $this->assertEquals($task->getTitle(), $taskToCompare->getTitle());
        $this->assertEquals($task->getContent(), $taskToCompare->getContent());

        $view = $form->createView();
        $children = $view->children;

}