<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
use DirectoryIterator;
use ReflectionClass;
use Closure;

class EndpointRouteBuilder
{
    use MethodTrait;

    private string $prefix = '';
    private IRouteBuilder $builder;

    public function __construct(IRouteBuilder $builder)
    {
        $this->builder = $builder;
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
     * Adds a route that matches HTTP requests for the specified path and HTTP method, or any HTTP method if it's not specified.
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
    public function mapControllers(?string $area = null, Closure $configure = null)
    {
        $options = new ControllerOptions();
        if ($configure) {
            $configure($options);
        }

        $namespace = LauncherProperties::getRootNamespace();
        $sourceRoot = dirname(LauncherProperties::getEntryPoint()->getFileName());

        if ($area) {
            $area = ucfirst($area);
            $namespace = $namespace . '\\' . $area;
            $sourceRoot = $sourceRoot . '/' . $area;
        }

        foreach (new DirectoryIterator($sourceRoot) as $dir) {
            if ($dir->isDir() && !$dir->isDot()) {
                foreach (new DirectoryIterator($dir->getRealPath()) as $file) {
                    if ($file->isFile()) {
                        $className = $namespace . '\\' . $dir->getFilename() . '\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
                        if (class_exists($className)) {
                            $parents = class_parents($className);
                            if (in_array(Controller::class, $parents)) {
                                $controller = new ReflectionClass($className);
                                foreach ($controller->getMethods() as $method) {
                                    $attribute = $method->getAttributes(Route::class);
                                    if ($attribute) {
                                        $route = $attribute[0]->newInstance();
                                        $path = $route->Path;
                                        if ($area) {
                                            $path = '/' . $area . $route->Path;
                                        }
                                        $this->builder->map($path, new ControllerRouteHandler([$className, $method->getName()], $options), $route->Method);
                                    }
                                }
                            }
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
