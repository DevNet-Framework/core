<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Exception;

use DevNet\System\MethodTrait;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Middleware\IMiddleware;
use DevNet\Web\Middleware\RequestDelegate;
use Throwable;

use function Devnet\System\await;

class ExceptionHandlerMiddleware implements IMiddleware
{
    use MethodTrait;

    private ?string $errorHandlingPath;

    public function __construct(?string $errorHandlingPath = null)
    {
        $this->errorHandlingPath = $errorHandlingPath;
    }

    public function async_invoke(HttpContext $context, RequestDelegate $next): void
    {
        try {
            await($next($context));
        } catch (Throwable $error) {
            if (PHP_SAPI == 'cli') {
                throw new $error;
            }

            // Need to remove the previous headers and body of the response the send only the error report.
            $context->Response->Body->truncate(0);
            $headerNames = array_keys($context->Response->Headers->getAll());
            foreach ($headerNames as $name) {
                $context->Response->Headers->remove($name);
            }

            if ($this->errorHandlingPath) {
                // Store the error to be handled later by the custom handler.
                $context->Items->add('ErrorException', $error);
                // Change the path to the custom handler
                $context->Request->Path = $this->errorHandlingPath;
                await($next($context));
                return;
            }

            // Handle the error exception as Http status code.
            $code = $error->getCode();
            $code = $code >= 400 ? $code : 500;
            $context->Response->setStatusCode($code);
        }
    }
}
