<?php declare(strict_types = 1);
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
    protected string $Path;
    protected int $StatusCode;

    public function __construct(string $path, int $statusCode = 302)
    {
        $this->Path = $path;
        $this->StatusCode = $statusCode;
    }

    public function execute(ActionContext $actionContext) : void
    {
        $httpContext    = $actionContext->HttpContext;
        $scheme         = $httpContext->Request->Uri->Scheme;
        $host           = $httpContext->Request->Uri->Host;
        $port           = $httpContext->Request->Uri->Port;

        $port           = $port != 80 && $port != '' ? ":".$port : '';

        if (strpos($this->Path, "/") !== 0 )
        {
            $this->Path = "/{$this->Path}";
        }

        $url = $scheme .'://'. $host . $port . $this->Path;
        $httpContext->Response->Headers->add("Location", $url);
    }
}
