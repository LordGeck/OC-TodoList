<?php
declare(strict_types=1);

namespace Tests\AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Security\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Security;

class TaskVoterTest extends TestCase
{
    private function createUser(int $id): User
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn($id);

        return $user;
    }

    public function provideCases()
    {
        yield 'anonymous cannot edit' => [
            'edit',
            (new Task())->setUser($this->createUser(1)),
            null,
            null,
            VoterInterface::ACCESS_DENIED
        ];
        yield 'anonymous cannot delete' => [
            'delete',
            (new Task())->setUser($this->createUser(1)),
            null,
            null,
            VoterInterface::ACCESS_DENIED
        ];
        yield 'non owner cannot edit' => [
            'edit',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(2),
            null,
            VoterInterface::ACCESS_DENIED
        ];
        yield 'non owner cannot delete' => [
            'delete',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(2),
            null,
            VoterInterface::ACCESS_DENIED
        ];
        yield 'owner can edit' => [
            'edit',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(1),
            null,
            VoterInterface::ACCESS_GRANTED
        ];
        yield 'owner can delete' => [
            'delete',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(1),
            null,
            VoterInterface::ACCESS_GRANTED
        ];
        yield 'admin can edit' => [
            'edit',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(3),
            'ROLE_ADMIN',
            VoterInterface::ACCESS_GRANTED
        ];
        yield 'admin can delete' => [
            'delete',
            (new Task())->setUser($this->createUser(1)),
            $this->createUser(3),
            'ROLE_ADMIN',
            VoterInterface::ACCESS_GRANTED
        ];
        yield 'permission not exist' => [
            'none',
            (new Task())->setUser($this->createUser(1)),
            null,
            null,
            VoterInterface::ACCESS_ABSTAIN
        ];
    }

    /**
     * @dataProvider provideCases
     */
    public function testVote(
        string $attribute,
        Task $task,
        ?User $user,
        $role,
        $expectedVote
    ) {
        $securityMock = $this->createMock(Security::class);
        $securityMock->method('isGranted')->willReturn($role === 'ROLE_ADMIN');
        $taskVoter = new TaskVoter($securityMock);

        $token = new AnonymousToken('secret', 'anonymous');
        if ($user) {
            $token = new UsernamePasswordToken(
                $user, 'credentials', 'memory'
            );
        }

        $this->assertSame(
            $expectedVote,
            $taskVoter->vote($token, $task, [$attribute])
        );
    }
}