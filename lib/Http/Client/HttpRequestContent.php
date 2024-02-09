<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
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
