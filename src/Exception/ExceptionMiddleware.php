<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Exception;

use DevNet\Core\Http\HttpContext;
use DevNet\Core\Middleware\IMiddleware;
use DevNet\Core\Middleware\RequestDelegate;
use DevNet\Core\View\ViewManager;
use DevNet\System\Async\Task;
use Throwable;

class ExceptionMiddleware implements IMiddleware
{
    private ?string $ErrorHandlingPath;

    public function __construct(?string $errorHandlingPath = null)
    {
        $this->ErrorHandlingPath = $errorHandlingPath;
    }

    public function __invoke(HttpContext $context, RequestDelegate $next): Task
    {
        set_error_handler(function (int $severity, string $message, string $file, int $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            return $next($context);
        } catch (Throwable $error) {
            $context->addAttribute('Error', $error);
            if ($this->ErrorHandlingPath) {
                $context->Request->Uri->Path = $this->ErrorHandlingPath;
                return $next($context);
            }
            return $this->handel($context);
        }
    }

    public function handel(HttpContext $context): task
    {
        $error = $context->Error;
        $data  = $this->parse($error);
        $view  = new ViewManager(__DIR__ . '/Views');
        $view->inject('ViewData', $data);
        $context->Response->Body->write($view->render('ExceptionView'));
        return Task::completedTask();
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

        $firstfile = $trace[0]['file'] ?? null;

        if ($error->getFile() == $firstfile) {
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
