<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http\Client;

use DevNet\System\Async\Tasks\Task;
use DevNet\Core\Http\Client\Internal\HttpRequestRawBuilder;
use DevNet\Core\Http\Client\Internal\HttpResponseParser;
use DevNet\Core\Http\HttpRequest;
use DevNet\Core\Http\HttpResponse;
use DevNet\System\Net\Socket;

abstract class HttpClientHandler
{
    protected HttpClientOptions $Options;

    abstract public function __construct(HttpClientOptions $options);

    public function send(HttpRequest $request): HttpResponse
    {
        return $this->sendAsync($request)->getAwaiter()->getResult();
    }

    public function sendAsync(HttpRequest $request): Task
    {
        $timeout = $this->Options->Timeout;

        return Task::Run(function () use ($request, $timeout) {
            $socket = new Socket($request->Uri->Host, $request->Uri->Port, $timeout, false);
            $socket->write(HttpRequestRawBuilder::build($request));

            $responseRaw = '';
            while (!$socket->eof()) {
                $responseRaw .= $socket->read(1024);
                yield;
            }

            $response = HttpResponseParser::parse($responseRaw);
            return $response;
        });
    }
}
