<?php

//src/AppBundle/Security/StrainVoter.php

namespace AppBundle\Security;

use AppBundle\Entity\Strain;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
        $user = $token->getUser();
        $strain = $subject;

        switch ($attribute) {
            case self::VIEW:
                if ($strain->isPublic()) {
                    return true;
                }

                if (!$user instanceof User) {
                    return false;
                }

                if ($strain->isAuthorizedUser($user)) {
                    return true;
                }

                if ($user->hasRole('ROLE_ADMIN')) {
                    return true;
                }
            break;
        }

        return false;
    }
}
