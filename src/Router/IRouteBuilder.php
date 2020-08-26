<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Router;

/**
* build a Router instance based on routes maping.
*/
interface IRouteBuilder
{
    public function mapRoute(string $name, string $pattern);
    
    public function build() : IRouter;
}