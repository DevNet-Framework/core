<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client\Internal;

use DevNet\Web\Http\Message\Headers;
use DevNet\Web\Http\Message\HttpResponse;

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
