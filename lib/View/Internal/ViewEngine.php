<?php

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
    private ViewManager $manager;
    private string $body        = '';
    private string $layoutName  = '';
    private string $sectionName = '';
    private array $sections     = [];

    public function __construct(ViewManager $manager)
    {
        $this->manager = $manager;
        stream_filter_register("view.opentag", ViewFilter::class);
    }

    public function &__get(string $name)
    {
        if ($name == 'ViewData') {
            return $this->manager->ViewData;
        }

        $service = $this->manager->Container->get($name);
        if ($service) {
            return $service;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . self::class . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . self::class . "::" . $name);
    }

    public function inject(string $serviceName, string $serviceType): void
    {
        $provider = $this->manager->Provider;
        if ($provider) {
            if ($provider->contains($serviceType)) {
                $this->manager->inject($serviceName, $provider->getService($serviceType));
            }
        } else {
            $service = new $serviceType;
            $this->manager->inject($serviceName, $service);
        }
    }

    public function layout(string $layoutName): void
    {
        if (!$this->layoutName) {
            $this->layoutName = $layoutName;
        }
    }

    public function section(string $sectionName): void
    {
        ob_start();
        $this->sectionName = $sectionName;
    }

    public function endSection(): void
    {
        $this->sections[$this->sectionName] = ob_get_clean();
    }

    public function renderSection(string $sectionName): void
    {
        if (isset($this->sections[$sectionName])) {
            echo $this->sections[$sectionName];
        }
    }

    public function renderPartial(string $templateName): void
    {
        extract($this->ViewData, EXTR_SKIP);

        if (!file_exists($this->manager->getPath($templateName))) {
            throw new InvalidArgumentException("Could not find the template: {$templateName}");
        }

        include 'php://filter/read=view.opentag/resource=' . $this->manager->getPath($templateName);
    }

    public function renderBody(): void
    {
        echo $this->body;
    }

    public function renderView(string $viewName): string
    {
        ob_start();
        try {
            $this->renderPartial($viewName);
        } catch (\Throwable $th) {
            ob_clean();
            throw $th;
        }

        $this->body = ob_get_clean();

        ob_start();
        $layoutPath = $this->manager->getPath($this->layoutName);
        if ($layoutPath) {
            try {
                include 'php://filter/read=view.opentag/resource=' . $layoutPath;
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
