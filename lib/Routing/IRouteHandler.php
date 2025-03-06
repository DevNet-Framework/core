<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Routing;

use DevNet\System\Async\Task;

interface IRouteHandler
{
    public mixed $Target { get; set; }

    public function handle(RouteContext $routeContext): Task;
}
