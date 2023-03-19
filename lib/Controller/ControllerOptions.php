<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller;

use DevNet\Web\Action\Binder\IModelBinder;
use DevNet\Web\Action\Binder\IValueProvider;
use DevNet\Web\Action\Binder\ModelBinderProvider;
use DevNet\Web\Action\Binder\Providers\CompositeValueProvider;
use DevNet\Web\Action\Binder\Providers\FileValueProvider;
use DevNet\Web\Action\Binder\Providers\FormValueProvider;
use DevNet\Web\Action\Binder\Providers\QueryValueProvider;
use DevNet\Web\Action\Binder\Providers\RouteValueProvider;
use DevNet\Web\Action\IActionFilter;

class ControllerOptions
{
    private string $controllerNamespace = 'Application\\Controllers';
    private string $viewDirectory = '../Views/';
    private array $actionFilters = [];
    private ?IModelBinder $modelBinder = null;
    private CompositeValueProvider $valueProviders;

    public function __construct()
    {
        $this->valueProviders = new CompositeValueProvider();
        $this->valueProviders->add(new RouteValueProvider());
        $this->valueProviders->add(new QueryValueProvider());
        $this->valueProviders->add(new FormValueProvider());
        $this->valueProviders->add(new FileValueProvider());
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->controllerNamespace = $namespace;
    }

    public function getControllerNamespace(): string
    {
        return $this->controllerNamespace;
    }

    public function setViewDirectory(string $directory)
    {
        $this->viewDirectory = $directory;
    }

    public function getViewDirectory(): string
    {
        return $this->viewDirectory;
    }

    public function addFilter(IActionFilter $actionFilter)
    {
        $this->actionFilters[get_class($actionFilter)] = $actionFilter;
        return $this;
    }

    public function getFilters(): array
    {
        return $this->actionFilters;
    }

    public function addValueProvider(IValueProvider $valueProvider)
    {
        $this->valueProviders->add($valueProvider);
        return $this;
    }

    public function addModelBinder(IModelBinder $modelBinder)
    {
        $this->modelBinder = $modelBinder;
        return $this;
    }

    public function getValueProviders(): CompositeValueProvider
    {
        return $this->valueProviders;
    }

    public function getModelBinderProvider(): ModelBinderProvider
    {
        return new ModelBinderProvider($this->modelBinder);
    }
}
