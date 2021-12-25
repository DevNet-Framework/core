<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http\Client;

class HttpRequestContent
{
    protected string $Content;
    protected string $ContentType;
    protected int $ContentLength;

    public function __construct(string $contentType, string $content)
    {
        $this->Content       = $content;
        $this->ContentType   = $contentType;
        $this->ContentLength = strlen($content);
    }
}
