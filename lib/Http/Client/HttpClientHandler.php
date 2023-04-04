<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

use DevNet\System\Async\Task;
use DevNet\Web\Http\Client\Internal\HttpRequestRawBuilder;
use DevNet\Web\Http\Client\Internal\HttpResponseParser;
use DevNet\Web\Http\HttpRequest;
use DevNet\Web\Http\HttpResponse;
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
            $socket = new Socket($request->Uri->Host, $request->Uri->Port, false, $timeout);
            $socket->write(HttpRequestRawBuilder::build($request));

            $responseHeaderRaw = '';
            do {
                $responseHeaderRaw .= yield $socket->readLine();
            } while (strpos($responseHeaderRaw, "\r\n\r\n") === false);

            $response = HttpResponseParser::parse($responseHeaderRaw);
            while (!$socket->eof()) {
                $responseBodyChunk = yield $socket->read(1024 * 4);
                $response->Body->write($responseBodyChunk);
            }

            return $response;
        });
    }
}
