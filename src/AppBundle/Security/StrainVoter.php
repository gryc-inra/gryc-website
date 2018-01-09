<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
        if (!in_array($attribute, [self::VIEW], true)) {
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

        if ($strain->isAllowedUser($token->getUser())) {
            return true;
        }

        return false;
    }
}
