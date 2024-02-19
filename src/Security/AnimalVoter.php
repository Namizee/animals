<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Animal;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AnimalVoter extends Voter
{
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Animal && in_array($attribute, [self::SHOW, self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject->getUser() === $user;
    }
}
