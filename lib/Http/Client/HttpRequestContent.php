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
    protected string $Content;
    protected string $ContentType;

    public function __construct(string $content, string $contentType)
    {
        $this->Content = $content;
        $this->ContentType = $contentType;
    }
}
