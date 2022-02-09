<?php
declare(strict_types = 1);

namespace AppBundle\Security;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        if (!$subject instanceof Task || !in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $task = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($task, $user);
            case self::DELETE:
                return $this->canDelete($task, $user);

        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Task $task, User $user): bool
    {
        return $user->getId() === $task->getUser()->getId();
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $this->canEdit($task, $user);
    }
}