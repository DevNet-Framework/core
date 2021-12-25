<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http\Client\Internal;

use DevNet\Core\Http\Headers;
use DevNet\Core\Http\HttpResponse;

class HttpResponseParser
{
    public static function parse(string $responseRaw): HttpResponse
    {
        $responseRaw    = explode("\r\n\r\n", $responseRaw, 2);
        $responseHeader = $responseRaw[0];
        $responseBody   = $responseRaw[1];
        $responseHeader = trim($responseHeader);

        $headers = explode(PHP_EOL, $responseHeader);
        $responseLine = array_shift($headers);
        $responseLine = explode(' ', $responseLine);

        foreach ($headers as $header) {
            explode(":", $header);
        }

        $response = new HttpResponse(new Headers($headers));
        $response->setProtocol($responseLine[0]);
        $response->setStatusCode($responseLine[1]);
        $response->Body->write($responseBody);

        return $response;
    }
}
