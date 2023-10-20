<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
