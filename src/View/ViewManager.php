<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\View;

use Artister\DevNet\View\Internal\ViewEngine;
use Artister\DevNet\View\Internal\ViewContainer;

class ViewManager
{
    private string $Directory;
    private ViewContainer $Container;

    public function __construct(string $Directory = null)
    {
        $this->setDirectory($Directory);
        $this->Container = new ViewContainer();
    }

    public function __get(string $Name)
    {
        return $this->$Name;
    }

    public function setDirectory($Directory)
    {
        $Directory = trim($Directory, '/');
        $this->Directory = $Directory;
    }

    public function getPath(string $PathName) : string
    {
        if ($PathName)
        {
            $PathName = trim($PathName, '/');
            return $this->Directory ."/". $PathName . ".phtml";
        }
        else
        {
            return $this->Directory;
        }
    }

    public function inject(string $Name, $Value)
    {
        $this->Container->addValue($Name, $Value);
    }

    public function render(string $viewName, $viewData = null) : string
    {
        $engine = new ViewEngine($this);
        return $engine->renderView($viewName, $viewData);
    }

    public function __invoke(string $viewName, $viewData = null) : string
    {
        return $this->render($viewName, $viewData);
    }
}