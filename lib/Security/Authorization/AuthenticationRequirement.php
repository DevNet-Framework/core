<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
