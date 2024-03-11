<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint;

use DevNet\System\Collections\Dictionary;
use DevNet\Web\Endpoint\Binder\IModelBinder;
use DevNet\Web\Endpoint\Binder\IValueProvider;
use DevNet\Web\Endpoint\Binder\ModelBinderProvider;
use DevNet\Web\Endpoint\Binder\Providers\CompositeValueProvider;
use DevNet\Web\Endpoint\Binder\Providers\FileValueProvider;
use DevNet\Web\Endpoint\Binder\Providers\FormValueProvider;
use DevNet\Web\Endpoint\Binder\Providers\QueryValueProvider;

class ControllerOptions
{
    public string $ViewLocation = '/Views';
    public ?IModelBinder $ModelBinder = null;
    private CompositeValueProvider $valueProviders;
    private array $actionFilters = [];

    public function __construct()
    {
        $this->valueProviders = new CompositeValueProvider();
        $this->valueProviders->add(new QueryValueProvider());
        $this->valueProviders->add(new FormValueProvider());
        $this->valueProviders->add(new FileValueProvider());
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

    public function getValueProviders(): CompositeValueProvider
    {
        return $this->valueProviders;
    }

    public function getModelBinderProvider(): ModelBinderProvider
    {
        return new ModelBinderProvider($this->ModelBinder);
    }
}
