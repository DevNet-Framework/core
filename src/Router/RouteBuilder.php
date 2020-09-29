<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Router;

use Artister\DevNet\Dependency\IServiceProvider;

class RouteBuilder implements IRouteBuilder
{
    private IServiceProvider $ServiceProvider;
    private ?IRouteHandler $DefaultHandler;
    private array $Routes;
    private string $Prefix = '';
    private string $Name = '';

    public function __construct(IServiceProvider $serviceProvider, IRouteHandler $defaultHandler = null)
    {
        $this->ServiceProvider = $serviceProvider;
        $this->DefaultHandler = $defaultHandler;
    }

    /**
     * set the route prefix.
     */
    public function group(string $prefix, callable $callback) : void
    {
        $this->Prefix = trim($prefix, '/');
        $callback($this);

        // reset the name for the next route
        $this->Prefix = '';
    }

    /**
     * set route name
     */
    public function name(string $name) : RouteBuilder
    {
        $this->Name = $name;
        return $this;
    }

    /**
     * mape the route
     */
    public function mapRoute(string $name, string $pattern, $target = null) : void
    {
        if ($target)
        {
            $routeHandler = new RouteHandler($this->ServiceProvider, $target);
        }
        else
        {
            if (!$this->DefaultHandler)
            {
                throw new \Exception("Default RouteHandler is missing");
            }

            $routeHandler = $this->DefaultHandler;
        }

        $pattern = $this->Prefix .'/'. trim($pattern, '/');
        $this->Routes[] = new Route($name, 'ANY', $pattern, $routeHandler);
        $this->Name = ''; // reset the name for the next route
    }

    /**
     * mape the route using the Http Verb GET.
     */
    public function mapGet(string $pattern, $target) : void
    {
        $this->mapVerb('GET', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb POST.
     */
    public function mapPost(string $pattern, $target) : void
    {
        $this->mapVerb('POST', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb PUT.
     */
    public function mapPut(string $pattern, $target) : void
    {
        $this->mapVerb('PUT', $pattern, $target);
    }

    /**
     * mape the route using the Http Verb DELETE.
     */
    public function mapDelete(string $pattern, $target) : void
    {
        $this->mapVerb('DELETE', $pattern, $target);
    }

    /**
     * mape the route using Http Verb.
     */
    public function mapVerb(string $verb, string $pattern, $target) : void
    {
        $pattern = $this->Prefix .'/'. trim($pattern, '/');
        $this->Routes[] = new Route($this->Name, $verb, $pattern, new RouteHandler($this->ServiceProvider, $target));
        $this->Name = ''; // reset the name for the next route
    }

    /**
     * build the router and retur RouteCollection instance.
     */
    public function build() : IRouter
    {
        $routeCollection = new RouteCollection();
        foreach ($this->Routes as $route) {
            $routeCollection->add($route);
        }
        return $routeCollection;
    }
}