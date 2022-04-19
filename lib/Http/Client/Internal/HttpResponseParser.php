<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client\Internal;

use DevNet\Web\Http\Headers;
use DevNet\Web\Http\HttpResponse;

class HttpResponseParser
{
    public static function parse(string $responseHeaderRaw): HttpResponse
    {
        $headers = explode(PHP_EOL, $responseHeaderRaw);
        $responseLine = array_shift($headers);
        $responseLine = explode(' ', $responseLine);

        foreach ($headers as $header) {
            explode(":", $header);
        }

        $response = new HttpResponse(new Headers($headers));
        $response->setProtocol($responseLine[0]);
        $response->setStatusCode($responseLine[1]);

        return $response;
    }
}
