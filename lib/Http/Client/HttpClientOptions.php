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
     * The host name of the url agress.
     */
    public string $BaseAddress = '';

    /**
     * the max timespan to wait.
     */
    public string $HttpVersion = 'HTTP/1.0';

    /**
     * the max times to wait in microseconds.
     */
    public int $Timeout = 0;
}
