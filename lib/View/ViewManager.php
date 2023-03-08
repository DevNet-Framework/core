<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\View;

use DevNet\System\Dependency\IServiceProvider;
use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\View\Internal\ViewEngine;
use DevNet\Web\View\Internal\ViewContainer;

class ViewManager
{
    private ViewContainer $container;
    private ?IServiceProvider $provider;
    private string $directory;
    private array $viewData = [];
    private ?object $model = null;

    public function &__get(string $name)
    {
        if (in_array($name, ['Container', 'Provider', 'Directory', 'ViewData', 'Model'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . self::class . "::" . $name);
    }

    public function __construct(string $directory = null, ?IServiceProvider $provider = null)
    {
        $this->setDirectory($directory);
        $this->provider  = $provider;
        $this->container = new ViewContainer();
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = rtrim($directory, '/');
    }

    public function getPath(string $pathName): string
    {
        if (!empty($pathName)) {
            $pathName = trim($pathName, '/');
            return $this->directory . "/" . $pathName . ".phtml";
        }

        return "";
    }

    public function setData(array $viewData): void
    {
        $this->viewData = $viewData;
    }

    public function inject(string $name, $value): void
    {
        $this->container->set($name, $value);
    }

    public function render(string $viewName, ?object $model = null): string
    {
        $this->model = $model;
        $engine = new ViewEngine($this);
        return $engine->renderView($viewName);
    }

    public function __invoke(string $viewName, ?object $model = null): string
    {
        return $this->render($viewName, $model);
    }
}
