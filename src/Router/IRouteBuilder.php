<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Router;

/**
 * build a Router instance based on routes maping.
 */
interface IRouteBuilder
{
    public function mapRoute(string $name, string $pattern, string ...$target);

    public function build(): IRouter;
}
