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
    /**
     * This method must set the value of following property
     * @var mixed $Target (represent the endpoint request handler)
     */
    public function __set(string $name, $value);

    public function handle(RouteContext $routeContext): Task;
}
