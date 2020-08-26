<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\View\Internal;

use Artister\DevNet\View\ViewManager;
use BadMethodCallException;
use InvalidArgumentException;

class ViewEngine
{
    private ViewManager $Manager;
    private string $LayoutName = '';
    private string $SectionName = '';
    private array $Sections = [];

    public function __construct(ViewManager $manager)
    {
        $this->Manager = $manager;
    }

    public function __get(string $name)
    {
        if ($this->Manager->Container->contains($name))
        {
            return $this->Manager->Container->getValue($name);
        }

        if (!property_exists($this, $name))
        {
            $class = get_class($this);
            return "Undefined property: {$class}::\${$name}";
        }

        $rp = new \ReflectionProperty($this, $name);
        if ($rp->isPrivate() || $rp->isProtected())
        {
            $class = get_class($this);
            return "Cannot access private or protected property {$class}::\${$name}";
        }

        return $this->$name;
    }

    public function layout(string $layoutName)
    {
        if (!$this->LayoutName)
        {
            $this->LayoutName = $layoutName;
        }
    }

    public function section(string $sectionName)
    {
        ob_start();
        $this->SectionName = $sectionName;
    }

    public function endSection()
    {
        $this->Sections[$this->SectionName] = ob_get_clean();
    }

    public function renderSection(string $sectionName)
    {
        if (isset($this->Sections[$sectionName]))
        {
            echo $this->Sections[$sectionName];
        }
        else
        {
            throw new InvalidArgumentException("Section $sectionName doesn't exist.");
        }
    }

    public function renderPartial(string $partialName)
    {
        $partialPath = $this->Manager->getPath($partialName);
        if (file_exists($partialPath))
        {
            include $partialPath;
        }
    }

    public function renderView(string $viewName, $viewData = null) : string
    {
        ob_start(); 

        $viewPath = $this->Manager->getPath($viewName);
        if (file_exists($viewPath))
        {
            include $viewPath;
            $layoutPath = $this->Manager->getPath($this->LayoutName);
            if (file_exists($layoutPath))
            {
                include $layoutPath;
            }
            else
            {
                throw new InvalidArgumentException("invalide path");
            }
        }
        else
        {
            throw new InvalidArgumentException("invalide path");
        }
        
        return ob_get_clean();
    }

    public function inject(string $serviceName)
    {
        $provider = $this->Manager->Provider;

        if ($provider->contains($serviceName))
        {
            $this->Dependencies[$serviceName] = $provider->getService($serviceName);
        }
    }
}