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
use DevNet\Web\View\ViewManager;
use Throwable;

use function Devnet\System\await;

class ExceptionPageMiddleware implements IMiddleware
{
    use MethodTrait;

    public function async_invoke(HttpContext $context, RequestDelegate $next): void
    {
        try {
            await($next($context));
        } catch (Throwable $error) {
            if (PHP_SAPI == 'cli') {
                throw new $error;
            }

            // Need to remove the previous headers and body of the response to send only the error report.
            $context->Response->Body->truncate(0);
            $headerNames = array_keys($context->Response->Headers->getAll());
            foreach ($headerNames as $name) {
                $context->Response->Headers->remove($name);
            }

            // Display the error exception page report.
            $data = $this->parse($error);
            $view = new ViewManager(__DIR__ . '/Views');
            await($context->Response->writeAsync($view->render('ExceptionView', $data)));
        }
    }

    public function parse(Throwable $error): array
    {
        $severities = [
            E_ERROR             => 'Fatal Error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parse Error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core Error',
            E_CORE_WARNING      => 'Core Warning',
            E_COMPILE_ERROR     => 'Compile Error',
            E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR        => 'User Error',
            E_USER_WARNING      => 'User Warning',
            E_USER_NOTICE       => 'User Notice',
            E_STRICT            => 'Strict Error',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User Deprecated'
        ];

        $trace = $error->getTrace();
        if ($error instanceof \ErrorException) {
            $severity = $severities[$error->getSeverity()];
        } else {
            $severity = $severities[E_ERROR];
        }

        $firstFile = $trace[0]['file'] ?? null;

        if ($error->getFile() == $firstFile) {
            array_shift($trace);
        }

        if ($error->getCode() == 0) {
            $code = '';
        } else {
            $code = $error->getCode();
        }

        $data['error']   = $severity;
        $data['message'] = $error->getMessage();
        $data['class']   = get_class($error);
        $data['code']    = $code;
        $data['file']    = $error->getFile();
        $data['line']    = $error->getLine();
        $data['trace']   = $trace;

        return $data;
    }
}