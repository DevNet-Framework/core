<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Action\Filters;

use DevNet\System\Async\Task;
use DevNet\Web\Action\ActionContext;
use DevNet\Web\Action\ActionDelegate;
use DevNet\Web\Action\IActionFilter;
use DevNet\Web\Http\HttpException;
use Attribute;

#[Attribute]
class HttpMethod implements IActionFilter
{
    private array $verbes;

    public function __construct(string ...$verbes)
    {
        $this->verbes = $verbes;
    }

    public function __invoke(ActionContext $context, ActionDelegate $next): Task
    {
        $allwoed = false;
        $httpMethod = $context->HttpContext->Request->Method;
        foreach ($this->verbes as $verbe) {
            if ($httpMethod == strtoupper($verbe)) {
                $allwoed = true;
                break;
            }
        }

        if (!$allwoed) {
            $context->HttpContext->Response->setStatusCode(405);
            throw new HttpException("\"{$httpMethod}\" Method Not Allowed", 405);
        }

        return $next($context);
    }
}
