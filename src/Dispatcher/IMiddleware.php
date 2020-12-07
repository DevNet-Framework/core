<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Dispatcher;

use Artister\DevNet\Http\HttpContext;
use Artister\System\Process\Task;

interface IMiddleware
{
    public function __invoke(HttpContext $context, RequestDelegate $next) : Task;
}
