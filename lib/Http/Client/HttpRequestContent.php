<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client;

class HttpRequestContent
{
    public string $Content;
    public string $ContentType;
    public int $ContentLength;

    public function __construct(string $contentType, string $content)
    {
        $this->Content       = $content;
        $this->ContentType   = $contentType;
        $this->ContentLength = strlen($content);
    }
}
