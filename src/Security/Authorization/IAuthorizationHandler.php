<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authorization;

use Artister\System\Process\Task;

interface IAuthorizationHandler
{
    public function handle(AuthorizationContext $context) : Task;
}