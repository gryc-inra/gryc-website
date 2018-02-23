<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
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

namespace AppBundle\Command;

use AppBundle\Service\UserManipulator;
use Symfony\Component\Console\Output\OutputInterface;

class DemoteUserCommand extends RoleCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('gryc:user:demote')
            ->setDescription('Remove a role to specified user');
    }

    protected function executeRoleCommand(UserManipulator $userManipulator, OutputInterface $output, $email, $role)
    {
        if ($userManipulator->removeRole($email, $role)) {
            $output->writeln(sprintf('Role "%s" has been removed to user "%s". This change will not apply until the user logs out and back in again.', $role, $email));
        } else {
            $output->writeln(sprintf('User "%s" didn\'t have "%s" role.', $email, $role));
        }
    }
}
