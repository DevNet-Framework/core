<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Mvc;

use Artister\DevNet\Mvc\Binder\IValueProvider;
use Artister\DevNet\Mvc\Binder\ValueProvider;
use Artister\DevNet\Mvc\Binder\CompositeValueProvider;
use Artister\DevNet\Mvc\Binder\IModelBinder;
use Artister\DevNet\Mvc\Binder\ModelBinderProvider;
use Artister\DevNet\Dependency\Factory\ServiceOptions;

class MvcOptions
{
    private string $ControllerNamespace = 'Application\\Controllers';
    private string $ViewDirectory = '../Views/';
    private array $ActionFilters = [];
    private CompositeValueProvider $compositeValueProvider;

    public function __construct()
    {
        $this->compositeValueProvider = new CompositeValueProvider();
    }

    public function setControllerNamespace(string $namespace)
    {
        $this->ControllerNamespace = $namespace;
    }

    public function getControllerNamespace() : string
    {
        return $this->ControllerNamespace;
    }

    public function setViewDirectory(string $directory)
    {
        $this->ViewDirectory = $directory;
    }

    public function getViewDirectory() : string
    {
        return $this->ViewDirectory;
    }

    public function addFilter(IActionFilter $actionFilter)
    {
        $this->ActionFilters[get_class($actionFilter)] = $actionFilter;
        return $this;
    }

    public function getFilters() : array
    {
        return $this->ActionFilters;
    }

    public function addValueProvider(IValueProvider $valueProvider)
    {
        $this->compositeValueProvider->add($valueProvider);
        return $this;
    }

    public function addModelBinder(IModelBinder $modelBinder)
    {
        $this->modelBinder = $modelBinder;
        return $this;
    }

    public function getValueProviders() : CompositeValueProvider
    {
        return $this->compositeValueProvider;
    }

    public function getModelBinderProvider() : ModelBinderProvider
    {
        return new ModelBinderProvider($this->modelBinder);
    }
}