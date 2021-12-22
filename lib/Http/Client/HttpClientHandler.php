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
use DevNet\Core\Http\HttpException;
use DevNet\Core\Http\HttpRequest;
use DevNet\Core\Http\HttpResponse;

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
        if (!$request->Headers->contains('host')) {
            $request->Headers->add('host', $request->Uri->Host);
        }

        $address    = $request->Uri->Host . ":" . $request->Uri->Port;
        $requestRaw = HttpRequestRawBuilder::build($request);
        
        return Task::Run(function () use ($address, $requestRaw) {
            $source = stream_socket_client($address, $errorCode, $errorMessage);
            if (!$source) {
                throw new HttpException($errorMessage, $errorCode);
            }

            fwrite($source, $requestRaw);
            stream_set_blocking($source, 0);

            if ($this->Options->Timeout) {
                stream_set_timeout($source, $this->Options->Timeout);
            }

            // get Response header
            $responseHeader = '';
            do {
                $responseHeader .= fgets($source);
                $info = stream_get_meta_data($source);
                if ($info['timed_out']) {
                    fclose($source);
                    throw new \Exception('HttpClient Connection timed out!');
                }
                yield;
            } while (strpos($responseHeader, "\r\n\r\n") === false);

            // get Response Body
            $responseBody = '';
            while (!feof($source)) {
                $responseBody .= fread($source, 1024);
                yield;
            }

            fclose($source);
            $response = HttpResponseParser::parse($responseHeader, $responseBody);
            return $response;
        });
    }
}
