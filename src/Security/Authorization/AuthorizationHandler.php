<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security\Authorization;

use Artister\System\Process\Task;

abstract class AuthorizationHandler implements IAuthorizationHandler
{
    public function handle(AuthorizationContext $context) : Task
    {
        foreach ($context->Requirements as $requirement)
        {
            if (get_class($this) == $requirement->getHandlerName()) {
                $this->handleRequirement($context, $requirement)->wait();
            }
        }

        return Task::completedTask();
    }

    abstract public function HandleRequirement(AuthorizationContext $context, IAuthorizationRequirement $requirement) : Task;
}