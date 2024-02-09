<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Task;

class AuthenticationRequirement implements IAuthorizationRequirement, IAuthorizationHandler
{
    public function getHandler(): IAuthorizationHandler
    {
        return $this;
    }

    public function Handle(AuthorizationContext $context): Task
    {
        $user = $context->User;

        if ($user) {
            if ($user->isAuthenticated()) {
                $context->succeed($this);
            }
        }

        return Task::completedTask();
    }
}
