<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\View;

use DevNet\System\IServiceProvider;
use DevNet\Core\View\Internal\ViewEngine;
use DevNet\Core\View\Internal\ViewContainer;

class ViewManager
{
    private string $Directory;
    private array $ViewData = [];
    private ViewContainer $Container;
    private ?IServiceProvider $Provider;

    public function __construct(string $Directory = null, ?IServiceProvider $provider = null)
    {
        $this->setDirectory($Directory);
        $this->Provider  = $provider;
        $this->Container = new ViewContainer();
    }

    public function __get(string $Name)
    {
        return $this->$Name;
    }

    public function setDirectory(string $directory): void
    {
        $this->Directory = trim($directory, '/');
    }

    public function setData(array $viewData)
    {
        $this->ViewData = $viewData;
    }

    public function getPath(string $pathName): string
    {
        if ($pathName) {
            $pathName = trim($pathName, '/');
            $path = $this->Directory . "/" . $pathName . ".phtml";

            if (file_exists($path)) {
                return $path;
            }

            return "";
        }

        return "";
    }

    public function inject(string $Name, $Value): void
    {
        $this->Container->set($Name, $Value);
    }

    public function render(string $viewName, ?object $model = null): string
    {
        $engine = new ViewEngine($this, $this->ViewData);
        return $engine->renderView($viewName, $model);
    }

    public function __invoke(string $viewName, ?object $model = null): string
    {
        return $this->render($viewName, $model);
    }
}
