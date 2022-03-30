<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Results;

use DevNet\Web\Controller\ActionContext;

class RedirectResult extends ActionResult
{
    private string $path;
    private int $statusCode;

    public function __construct(string $path, int $statusCode = 302)
    {
        $this->path = $path;
        $this->statusCode = $statusCode;
    }

    public function execute(ActionContext $actionContext): void
    {
        $httpContext = $actionContext->HttpContext;
        $scheme      = $httpContext->Request->Uri->Scheme;
        $host        = $httpContext->Request->Uri->Host;
        $port        = $httpContext->Request->Uri->Port;
        $port        = $port != 80 && $port != '' ? ":" . $port : '';

        if (strpos($this->path, "/") !== 0) {
            $this->path = "/{$this->path}";
        }

        $url = $scheme . '://' . $host . $port . $this->path;
        $httpContext->Response->Headers->add("Location", $url);
    }
}
