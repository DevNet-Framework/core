<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Controller\Results;

use DevNet\Web\Controller\ActionContext;

class JsonResult extends ActionResult
{
    private string $content;
    private int $statusCode;

    public function __construct($data, int $statusCode = 200)
    {
        $this->content = json_encode($data);
        $this->statusCode = $statusCode;
    }

    public function execute(ActionContext $actionContext): void
    {
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add("Content-Type", "application/json");
        $httpContext->Response->Body->write($this->content);
        $httpContext->Response->setStatusCode($this->statusCode);
    }
}
