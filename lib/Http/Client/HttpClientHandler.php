<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

use DevNet\System\Async\Task;
use DevNet\Web\Http\Client\Internal\HttpRequestRawBuilder;
use DevNet\Web\Http\Client\Internal\HttpResponseParser;
use DevNet\Web\Http\Message\HttpRequest;
use DevNet\Web\Http\Message\HttpResponse;
use DevNet\System\Net\Socket;

use function DevNet\System\async;
use function DevNet\System\await;

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
        $sendAsync = async(function ($request) {
            $socket = new Socket($request->Host->Name, $request->Host->Port, $this->Options->Timeout);
            $socket->write(HttpRequestRawBuilder::build($request));
            $responseHeaderRaw = '';
            do {
                $responseHeaderRaw .= await($socket->readLineAsync());
            } while (strpos($responseHeaderRaw, "\r\n\r\n") === false);

            $response = HttpResponseParser::parse($responseHeaderRaw);
            while (!$socket->EndOfStream) {
                $responseBodyChunk = await($socket->readAsync(1024 * 4));
                $response->Body->write($responseBodyChunk);
            }

            return $response;
        });

        return $sendAsync($request);
    }
}
