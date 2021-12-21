<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client\Internal;

use DevNet\System\IO\Stream;
use DevNet\Web\Http\HttpResponse;

class HttpResponseParser
{
    public static function parse(string $responseHeader, string $responseBody): HttpResponse
    {
        $responseHeader = trim($responseHeader);
        $headers = explode(PHP_EOL, $responseHeader);
        $responseLine = array_shift($headers);
        $responseLine = explode(' ', $responseLine);

        foreach ($headers as $header) {
            explode(":", $header);
        }

        $body = new Stream('php://temp', 'r+');
        $response = new HttpResponse($body, $headers);
        $response->setProtocol($responseLine[0]);
        $response->setStatusCode($responseLine[1]);
        $response->writeAsync($responseBody);

        return $response;
    }
}
