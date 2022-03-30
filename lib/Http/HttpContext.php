<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\Exceptions\PropertyException;

class HttpContext
{
    private HttpRequest $request;
    private HttpResponse $response;
    private FeatureCollection $features;
    private array $attributes = [];

    public function __get(string $name)
    {
        if (in_array($name, ['Request', 'Response', 'Features'])) {
            $property = lcfirst($name);
            return $this->$property;
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property" . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property" . get_class($this) . "::" . $name);
    }

    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->features = new FeatureCollection();
    }

    public function addAttribute(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function removeAttribute(string $name): bool
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
            return true;
        }

        return false;
    }
}
