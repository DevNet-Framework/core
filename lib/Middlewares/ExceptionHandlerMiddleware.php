<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Middlewares;

use DevNet\Core\Diagnostics\ExceptionHandler;
use DevNet\Http\Message\HttpContext;
use DevNet\Http\Middleware\IMiddleware;
use DevNet\Http\Middleware\RequestDelegate;
use DevNet\System\MethodTrait;
use Throwable;

use function DevNet\System\await;

class ExceptionHandlerMiddleware implements IMiddleware
{
    use MethodTrait;

    private ?string $errorHandlingPath;
    private ExceptionHandler $handler;

    public function __construct(?string $errorHandlingPath = null)
    {
        $this->errorHandlingPath = $errorHandlingPath;
        $this->handler = new ExceptionHandler();
    }

    public function async_invoke(HttpContext $context, RequestDelegate $next): void
    {
        try {
            // Must throw the previous error exception if it exists, before catching the next one.
            $error = $context->Items['ErrorException'] ?? null;
            if ($error) {
                throw $error;
            }
            await($next($context));
        } catch (Throwable $error) {
            if (PHP_SAPI == 'cli') {
                throw new $error;
            }

            // Must clear the previous response body and headers before sending the error report.
            $context->Response->Body->truncate(0);
            $headerNames = array_keys($context->Response->Headers->getAll());
            foreach ($headerNames as $name) {
                $context->Response->Headers->remove($name);
            }

            if ($this->errorHandlingPath) {
                // Store the error to be handled later by the custom handler.
                $context->Items->add('ErrorException', $error);
                // Change the path to the custom handler
                $context->Request->Url->Path = $this->errorHandlingPath;
                await($next($context));
                return;
            }

            // Display the error exception page report.
            $report = $this->handler->handle($error);
            await($context->Response->writeAsync($report));
        }
    }
}
