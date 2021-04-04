<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\View\Internal;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\View\ViewManager;
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
        if ($this->Manager->Container->contains($name))
        {
            return $this->Manager->Container->getValue($name);
        }

        if (!property_exists($this, $name))
        {
            $class = get_class($this);
            throw new PropertyException("Undefined property: {$class}::\${$name}");
        }

        $rp = new \ReflectionProperty($this, $name);
        if ($rp->isPrivate() || $rp->isProtected())
        {
            $class = get_class($this);
            throw new PropertyException("Cannot access private or protected property {$class}::\${$name}");
        }

        return $this->$name;
    }

    public function layout(string $layoutName) : void
    {
        if (!$this->LayoutName)
        {
            $this->LayoutName = $layoutName;
        }
    }

    public function section(string $sectionName) : void
    {
        ob_start();
        $this->SectionName = $sectionName;
    }

    public function endSection() : void
    {
        $this->Sections[$this->SectionName] = ob_get_clean();
    }

    public function renderSection(string $sectionName) : void
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

    public function renderPartial(string $partialName) : void
    {
        $partialPath = $this->Manager->getPath($partialName);
        if (file_exists($partialPath))
        {
            include $partialPath;
        }
    }

    public function renderBody() : void
    {
        echo $this->Body;
    }

    public function renderView(string $viewName, ?object $model = null) : string
    {
        ob_start();
        $viewPath = $this->Manager->getPath($viewName);

        if ($viewPath)
        {
            try
            {
                include $viewPath;
            }
            catch (\Throwable $th)
            {
                ob_clean();
                throw $th;
            }
        }
        else
        {
            throw new InvalidArgumentException("Vew name : {$viewName} Not found");
        }

        $this->Body = ob_get_clean();

        ob_start();
        $layoutPath = $this->Manager->getPath($this->LayoutName);

        if ($layoutPath)
        {
            try
            {
                include $layoutPath;
            }
            catch (\Throwable $th)
            {
                ob_clean();
                throw $th;
            }
        }
        else
        {
            $this->renderBody();
        }
        
        return ob_get_clean();
    }

    public function inject(string $serviceName) : void
    {
        $provider = $this->Manager->Provider;

        if ($provider->contains($serviceName))
        {
            $this->Dependencies[$serviceName] = $provider->getService($serviceName);
        }
    }
}
