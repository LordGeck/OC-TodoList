<?php
declare(strict_types=1);

namespace Tests\AppBundle\Form;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'test',
            'content' => 'test',
        ];

        $model = new Task();
        $expected = new Task();
        $expected->setTitle('test')
            ->setContent('test')
            ->setCreatedAt($model->getCreatedAt());

        $form = $this->factory->create(TaskType::class, $model);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
    }
}