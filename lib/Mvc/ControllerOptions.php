<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Mvc;

use DevNet\System\Loader\LauncherProperties;
use DevNet\Core\Mvc\Binder\IValueProvider;
use DevNet\Core\Mvc\Binder\IModelBinder;
use DevNet\Core\Mvc\Binder\ModelBinderProvider;
use DevNet\Core\Mvc\Binder\CompositeValueProvider;
use DevNet\Core\Mvc\Providers\FileValueProvider;
use DevNet\Core\Mvc\Providers\FormValueProvider;
use DevNet\Core\Mvc\Providers\QueryValueProvider;
use DevNet\Core\Mvc\Providers\RouteValueProvider;

class ControllerOptions
{
    private string $ControllerNamespace;
    private string $ViewDirectory = '../Views/';
    private array $ActionFilters = [];
    private ?IModelBinder $ModelBinder = null;
    private CompositeValueProvider $ValueProviders;

    public function __construct()
    {
        $this->ControllerNamespace = LauncherProperties::getNamespace() . '\\Controllers';
        $this->ValueProviders = new CompositeValueProvider();
        $this->ValueProviders->add(new RouteValueProvider());
        $this->ValueProviders->add(new QueryValueProvider());
        $this->ValueProviders->add(new FormValueProvider());
        $this->ValueProviders->add(new FileValueProvider());
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->ControllerNamespace = $namespace;
    }

    public function getControllerNamespace(): string
    {
        return $this->ControllerNamespace;
    }

    public function setViewDirectory(string $directory)
    {
        $this->ViewDirectory = $directory;
    }

    public function getViewDirectory(): string
    {
        return $this->ViewDirectory;
    }

    public function addFilter(IActionFilter $actionFilter)
    {
        $this->ActionFilters[get_class($actionFilter)] = $actionFilter;
        return $this;
    }

    public function getFilters(): array
    {
        return $this->ActionFilters;
    }

    public function addValueProvider(IValueProvider $valueProvider)
    {
        $this->ValueProviders->add($valueProvider);
        return $this;
    }

    public function addModelBinder(IModelBinder $modelBinder)
    {
        $this->ModelBinder = $modelBinder;
        return $this;
    }

    public function getValueProviders(): CompositeValueProvider
    {
        return $this->ValueProviders;
    }

    public function getModelBinderProvider(): ModelBinderProvider
    {
        return new ModelBinderProvider($this->ModelBinder);
    }
}
