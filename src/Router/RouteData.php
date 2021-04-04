<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router;

/**
 * Collection of informations about the result of the routing proccess.
 */
class RouteData
{
    public array $Routers = [];
    public array $Values = [];

    public function __get(string $name)
    {
        return $this->$name;
    }
}
