<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Authorization;

use DevNet\System\Async\Task;

class AuthenticationRequirement extends AuthorizationHandler implements IAuthorizationRequirement
{
    public function __get(string $name)
    {
        return $this->$name;
    }

    public function getHandlerName() : string
    {
        return get_class($this);
    }

    public function HandleRequirement(AuthorizationContext $context, IAuthorizationRequirement $requirement) : Task
    {
        $user = $context->User;

        if ($user)
        {
            if ($user->isAuthenticated()) {
                $context->success($requirement);
            }
        }

        return Task::completedTask();
    }
}
