<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Controller;

use DevNet\Core\Controller\Binder\IValueProvider;
use DevNet\Core\Controller\Binder\IModelBinder;
use DevNet\Core\Controller\Binder\ModelBinderProvider;
use DevNet\Core\Controller\Binder\CompositeValueProvider;
use DevNet\Core\Controller\Providers\FileValueProvider;
use DevNet\Core\Controller\Providers\FormValueProvider;
use DevNet\Core\Controller\Providers\QueryValueProvider;
use DevNet\Core\Controller\Providers\RouteValueProvider;

class ControllerOptions
{
    private string $ControllerNamespace = 'Application\\Controllers';
    private string $ViewDirectory = '../Views/';
    private array $ActionFilters = [];
    private ?IModelBinder $ModelBinder = null;
    private CompositeValueProvider $ValueProviders;

    public function __construct()
    {
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
        $this->ValueProviders->add($valueProvider);
        return $this;
    }

    public function addModelBinder(IModelBinder $modelBinder)
    {
        $this->ModelBinder = $modelBinder;
        return $this;
    }

    public function getValueProviders() : CompositeValueProvider
    {
        return $this->ValueProviders;
    }

    public function getModelBinderProvider() : ModelBinderProvider
    {
        return new ModelBinderProvider($this->ModelBinder);
    }
}
