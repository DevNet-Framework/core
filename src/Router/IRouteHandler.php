<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Router;

use Artister\System\Process\Task;

interface IRouteHandler
{
    /**
     * This method must set the value of following properity
     * @var mixed $Target (represent the endpoit request handler)
     */
    public function __set(string $name, $value);

    public function handle(RouteContext $routeContext) : Task;
}