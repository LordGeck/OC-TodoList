<?php
declare(strict_types = 1);

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testId()
    {
        $this->assertNull($this->user->getId());
    }

    public function testUsername()
    {
        $username = 'Username';
        $this->user->setUsername($username);
        $this->assertSame($username, $this->user->getUsername());
    }

    public function testSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testPassword()
    {
        $password = password_hash('password', PASSWORD_BCRYPT);
        $this->user->setPassword($password);
        $this->assertSame($password, $this->user->getPassword());
    }

    public function testEmail()
    {
        $email = 'test@email.com';
        $this->user->setEmail($email);
        $this->assertSame($email, $this->user->getEmail());
    }

    public function testRoles()
    {
        $roles = ['ROLE_USER'];
        $this->user->setRoles($roles);
        $this->assertSame($roles, $this->user->getRoles());
    }

    public function testEraseCredentials()
    {
        $this->assertNull($this->user->eraseCredentials());
    }

    public function testTasks()
    {
        $task = new Task();
        $task->setUser($this->user);
        $this->user->addTask($task);
        $this->assertCount(1, $this->user->getTasks());

        $tasks = $this->user->getTasks();
        $this->assertSame($this->user->getTasks(), $tasks);

        $this->user->removeTask($task);
        $this->assertCount(0, $this->user->getTasks());
    }

}
