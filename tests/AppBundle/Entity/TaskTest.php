<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    private $task;

    public function setUp()
    {
        $this->task = new Task();
    }

    public function testId()
    {
        $this->assertNull($this->task->getId());
    }

    public function testCreatedAt()
    {
        $date = new \DateTime();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $title = 'Title';
        $this->task->setTitle($title);
        $this->assertSame($title, $this->task->getTitle());
    }

    public function testContent()
    {
        $content = 'content';
        $this->task->setContent($content);
        $this->assertSame($content, $this->task->getContent());
    }

    public function testIsDone()
    {
        $bool = true;
        $this->task->toggle($bool);
        $this->assertSame($bool, $this->task->isDone());
    }

    public function testUser()
    {
        $this->task->setUser(new User());
        $this->assertInstanceOf(User::class, $this->task->getUser());
    }

}
