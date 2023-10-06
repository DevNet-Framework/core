<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Runtime\LauncherProperties;
use DevNet\Web\Endpoint\ControllerRouteHandler;
use DevNet\Web\Endpoint\Route;
use DevNet\Web\Routing\IRouteBuilder;
use DevNet\Web\Routing\IRouteHandler;
use DevNet\Web\Routing\IRouter;
use DevNet\Web\Routing\Route as Router;
use ReflectionClass;

class EndpointRouteBuilder
{
    private string $prefix = '';
    private IRouteBuilder $builder;
    private IServiceProvider $services;

    public function __construct(IServiceProvider $services)
    {
        $this->services = $services;
        $this->builder = $services->getService(IRouteBuilder::class);
    }

    /**
     * Maps a group of routes under the same prefix path.
     */
    public function mapGroup(string $prefix, callable $callback): void
    {
        $this->prefix = trim($prefix, '/');
        $callback($this);
        // Clear the prefix for the routes outside the group.
        $this->prefix = '';
    }

    /**
     * Maps the route.
     */
    public function mapRoute(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler);
    }

    /**
     * Maps the route using the Http Verb GET.
     */
    public function mapGet(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, 'GET');
    }

    /**
     * Maps the route using the Http Verb POST.
     */
    public function mapPost(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, 'POST');
    }

    /**
     * Maps the route using the Http Verb PUT.
     */
    public function mapPut(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, 'PUT');
    }

    /**
     * Maps the route using the Http Verb DELETE.
     */
    public function mapDelete(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, 'DELETE');
    }

    /**
     * Maps the route using the Http Verb PATCH.
     */
    public function mapPatch(string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, 'PATCH');
    }

    /**
     * Maps the route using Http Verb.
     */
    public function mapVerb(string $verb, string $path, string|callable|array $handler): IRouteHandler
    {
        $pattern = $this->prefix . '/' . trim($path, '/');
        return $this->builder->map($pattern, $handler, $verb);
    }

    /**
     * Maps the route using Http Verb.
     */
    public function mapControllers()
    {
        $options = $this->services->getService(ControllerOptions::class);
        $namespace = $options->ControllerNamespace;
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
                            $this->builder->Routes[] = new Router(new ControllerRouteHandler([$controllerName, $method->getName()]), $route->Path, $route->Method);
                        }
                    }
                }
            }
        }
    }

    /**
     * Builds IRouter from the routes specified in the Routes property.
     */
    public function build(): IRouter
    {
        return $this->builder->build();
    }
}
