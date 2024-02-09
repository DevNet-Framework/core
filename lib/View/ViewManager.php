<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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

    public function __construct(string $directory, ?IServiceProvider $provider = null)
    {
        $this->directory = rtrim($directory, '/');
        $this->provider  = $provider;
        $this->container = new ViewContainer();
    }

    public function &__get(string $name)
    {
        if (in_array($name, ['Container', 'Provider', 'Directory', 'ViewData'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . self::class . "::" . $name);
    }

    public function getPath(string $pathName): string
    {
        if (!empty($pathName)) {
            $pathName = trim($pathName, '/');
            return $this->directory . "/" . $pathName . ".phtml";
        }

        return "";
    }

    public function inject(string $name, $value): void
    {
        $this->container->set($name, $value);
    }

    public function render(string $viewName, array $viewData = []): string
    {
        $this->viewData = $viewData;
        $engine = new ViewEngine($this);
        return $engine->renderView($viewName);
    }

    public function __invoke(string $viewName, array $viewData = []): string
    {
        return $this->render($viewName, $viewData);
    }
}
