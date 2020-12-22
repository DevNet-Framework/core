<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\Mvc\Binder\IValueProvider;
use Artister\Web\Mvc\Binder\CompositeValueProvider;
use Artister\Web\Mvc\Binder\IModelBinder;
use Artister\Web\Mvc\Binder\ModelBinderProvider;

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