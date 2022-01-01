<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Mvc\Results;

use DevNet\Web\Mvc\ActionContext;

class JsonResult extends ActionResult
{
    protected string $Content;
    protected int $StatusCode;

    public function __construct($data, int $statusCode = 200)
    {
        $this->Content    = json_encode($data);
        $this->StatusCode = $statusCode;
    }

    public function execute(ActionContext $actionContext): void
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add("Content-Type", "application/json");
        $httpContext->Response->Body->write($this->Content);
        $httpContext->Response->setStatusCode($this->StatusCode);
    }
}
