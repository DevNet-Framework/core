<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\View\Internal;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Core\View\ViewManager;
use InvalidArgumentException;

class ViewEngine
{
    private ViewManager $Manager;
    private string $Body;
    private string $LayoutName  = '';
    private string $SectionName = '';
    private array $Sections     = [];

    public function __construct(ViewManager $manager, array $viewData = [])
    {
        $this->Manager = $manager;
    }

    public function __get(string $name)
    {
        if ($this->Manager->Container->has($name)) {
            return $this->Manager->Container->get($name);
        }

        if (!property_exists($this, $name)) {
            $class = get_class($this);
            throw new PropertyException("Undefined property: {$class}::\${$name}");
        }

        $rp = new \ReflectionProperty($this, $name);
        if ($rp->isPrivate() || $rp->isProtected()) {
            $class = get_class($this);
            throw new PropertyException("Cannot access private or protected property {$class}::\${$name}");
        }

        return $this->$name;
    }

    public function inject(string $serviceName, string $serviceType): void
    {
        $provider = $this->Manager->Provider;
        if ($provider) {
            if ($provider->contains($serviceType)) {
                $this->Manager->inject($serviceName, $provider->getService($serviceType));
            }
        } else {
            $service = new $serviceType;
            $this->Manager->inject($serviceName, $service);
        }
    }

    public function layout(string $layoutName): void
    {
        if (!$this->LayoutName) {
            $this->LayoutName = $layoutName;
        }
    }

    public function section(string $sectionName): void
    {
        ob_start();
        $this->SectionName = $sectionName;
    }

    public function endSection(): void
    {
        $this->Sections[$this->SectionName] = ob_get_clean();
    }

    public function renderSection(string $sectionName): void
    {
        if (isset($this->Sections[$sectionName])) {
            echo $this->Sections[$sectionName];
        }
    }

    public function renderPartial(string $partialName): void
    {
        $partialPath = $this->Manager->getPath($partialName);
        if (file_exists($partialPath)) {
            include $partialPath;
        }
    }

    public function renderBody(): void
    {
        echo $this->Body;
    }

    public function renderView(string $viewName, ?object $model = null): string
    {
        ob_start();
        $viewPath = $this->Manager->getPath($viewName);

        if (file_exists($viewPath)) {
            try {
                include $viewPath;
            } catch (\Throwable $th) {
                ob_clean();
                throw $th;
            }
        } else {
            throw new InvalidArgumentException("Could not find the view: {$viewPath}");
        }

        $this->Body = ob_get_clean();

        ob_start();
        $layoutPath = $this->Manager->getPath($this->LayoutName);

        if ($layoutPath) {
            try {
                include $layoutPath;
            } catch (\Throwable $th) {
                ob_clean();
                throw $th;
            }
        } else {
            $this->renderBody();
        }

        return ob_get_clean();
    }
}
