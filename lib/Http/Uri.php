<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class Uri
{
    public ?string $Scheme = null;
    public ?string $Host   = null;
    public ?int    $Port   = null;
    public ?string $Path   = null;
    public ?string $Query  = null;

    public function __construct(?string $url = null)
    {
        if (!empty($url)) {
            $this->Scheme = parse_url($url, PHP_URL_SCHEME);
            $this->Host   = parse_url($url, PHP_URL_HOST);
            $this->Port   = parse_url($url, PHP_URL_PORT);
            $this->Path   = parse_url($url, PHP_URL_PATH);
            $this->Query  = parse_url($url, PHP_URL_QUERY);
        }

        if (!$this->Port) {
            if ($this->Scheme == 'https://') {
                $this->Port = 443;
            } else {
                $this->Port = 80;
            }
        }
    }

    public function __toString()
    {
        $uri = '';
        $uri .= !empty($this->Scheme) ? $this->Scheme . "://" : null;
        $uri .= $this->Host;
        $uri .= ($this->Port != 80 && $this->Port != 443) ? ':' . $this->Port : null;
        $uri .= !empty($this->Path) ? $this->Path : '/';
        $uri .= !empty($this->Query) ? '?' . $this->Query : null;

        return $uri;
    }
}
