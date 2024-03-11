<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Message;

use DevNet\System\IO\Stream;
use DevNet\System\PropertyTrait;

abstract class HttpMessage
{
    use PropertyTrait;

    protected string $protocol = 'HTTP/1.0';
    protected Headers $headers;
    protected Cookies $cookies;
    protected ?Stream $body;

    public function get_Protocol(): string
    {
        return $this->protocol;
    }

    public function get_Headers(): Headers
    {
        return $this->headers;
    }

    public function get_Cookies(): Cookies
    {
        return $this->cookies;
    }

    public function get_Body(): ?Stream
    {
        return $this->body;
    }

    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
    }
}
