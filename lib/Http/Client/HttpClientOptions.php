<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

class HttpClientOptions
{
    /**
     * The host name of the url address.
     */
    public string $BaseAddress = '';

    /**
     * The HTTP version.
     */
    public string $HttpVersion = 'HTTP/1.0';

    /**
     * The max times to wait in seconds.
     */
    public ?float $Timeout = null;
}
