<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\MethodTrait;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Endpoint\Route;
use DevNet\Web\Routing\IRouteBuilder;
use DevNet\Web\Routing\IRouteHandler;
use DevNet\Web\Routing\IRouter;
use DevNet\Web\Routing\RouteHandler;
use ReflectionClass;

class EndpointRouteBuilder
{
    use MethodTrait;

    private string $prefix = '';
    private IRouteBuilder $builder;
    private ControllerOptions $options;

    public function __construct(IRouteBuilder $builder, ControllerOptions $options)
    {
        $this->builder = $builder;
        $this->options = $options;
    }

    /**
     * Adds a group of routes that all prefixed with the specified prefix.
     */
    public function mapGroup(string $prefix, callable $callback): void
    {
        $this->prefix = trim($prefix, '/');
        $callback($this);
        // Clear the prefix for the routes outside the group.
        $this->prefix = '';
    }

    /**
     * Adds a route that matches HTTP requests for the specified path and HTTP method, or any HTTP method if it's not specifed.
     */
    public function mapRoute(string $path, string|callable|array $handler, ?string $method = null): IRouteHandler
    {
        $path = $this->prefix . '/' . trim($path, '/');
        $routeHandler = new RouteHandler($handler);
        return $this->builder->map($path, $routeHandler, $method);
    }

    /**
     * Adds a route that matches HTTP GET requests for the specified path.
     */
    public function mapGet(string $path, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($path, $handler, 'GET');
    }

    /**
     * Adds a route that matches HTTP POST requests for the specified path.
     */
    public function mapPost(string $path, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($path, $handler, 'POST');
    }

    /**
     * Adds a route that matches HTTP PUT requests for the specified path.
     */
    public function mapPut(string $path, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($path, $handler, 'PUT');
    }

    /**
     * Adds a route that matches HTTP DELETE requests for the specified path.
     */
    public function mapDelete(string $path, string|callable|array $handler): IRouteHandler
    {
        $path = $this->prefix . '/' . trim($path, '/');
        return $this->mapRoute($path, $handler, 'DELETE');
    }

    /**
     * Adds a route that matches HTTP PATCH requests for the specified path.
     */
    public function mapPatch(string $path, string|callable|array $handler): IRouteHandler
    {
        return $this->mapRoute($path, $handler, 'PATCH');
    }

    /**
     * Maps routes from controllers.
     */
    public function mapControllers()
    {
        $namespace = $this->options->ControllerNamespace;
        $dir = str_replace("\\", "/", $namespace);
        $dir = LauncherProperties::getRootDirectory() . strstr($dir, '/');

        $paths = scandir($dir);
        foreach ($paths as $path) {
            if (!in_array($path, array(".", ".."))) {
                $controllerName = $namespace . "\\" . pathinfo($path, PATHINFO_FILENAME);
                if (class_exists($controllerName)) {
                    $controller = new ReflectionClass($controllerName);
                    foreach ($controller->getMethods() as $method) {
                        $attribute = $method->getAttributes(Route::class);
                        if ($attribute) {
                            $route = $attribute[0]->newInstance();
                            $this->builder->map($route->Path, new EndpointRouteHandler([$controllerName, $method->getName()]), $route->Method);
                        }
                    }
                }
            }
        }
    }

    /**
     * Builds IRouter from the specified routes.
     */
    public function build(): IRouter
    {
        return $this->builder->build();
    }
}
