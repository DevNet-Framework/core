<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class HttpContext
{
    private HttpRequest $Request;
    private HttpResponse $Response;
    private FeatureCollection $Features;
    private array $Attributes = [];

    public function __get(string $name)
    {
        switch ($name) {
            case 'Request':
            case 'Response':
            case 'Features':
                return $this->$name;
                break;
            default:
                return $this->Attributes[$name] ?? null;
                break;
        }
    }

    public function __construct(HttpRequest $request, HttpResponse $response)
    {
        $this->Request = $request;
        $this->Response = $response;
        $this->Features = new FeatureCollection();
    }

    public function addAttribute(string $name, $value): void
    {
        $this->Attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->Attributes[$name] ?? null;
    }

    public function removeAttribute(string $name): bool
    {
        if (isset($this->Attributes[$name])) {
            unset($this->Attributes[$name]);
            return true;
        }

        return false;
    }
}
