<?php

namespace AppBundle\Security;

use AppBundle\Entity\Strain;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StrainVoter extends Voter
{
    // List possible actions
    const VIEW = 'VIEW';

    protected function supports($attribute, $subject)
    {
        // If the attribute isn't supported by this voter, return false
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        // The voter is used only for Strain object, else return false
        if (!$subject instanceof Strain) {
            return false;
        }

        // Else the voter support the vote
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $strain = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($strain, $token);
        }

        return false;
    }

    private function canView(Strain $strain, TokenInterface $token)
    {
        if ($strain->isPublic()) {
            return true;
        }

        if (!$token->getUser() instanceof User) {
            return false;
        }

        if ($strain->isAuthorizedUser($token->getUser())) {
            return true;
        }

        return false;
    }
}
