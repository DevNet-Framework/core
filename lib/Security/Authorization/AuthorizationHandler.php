<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Async\Task;

abstract class AuthorizationHandler implements IAuthorizationHandler
{
    public function handle(AuthorizationContext $context): Task
    {
        foreach ($context->Requirements as $requirement) {
            if (get_class($this) == $requirement->getHandlerName()) {
                $this->handleRequirement($context, $requirement)->wait();
            }
        }

        return Task::completedTask();
    }

    abstract public function handleRequirement(AuthorizationContext $context, IAuthorizationRequirement $requirement): Task;
}
