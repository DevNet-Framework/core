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
    public Host $Host;
    public ?string $Path   = null;
    public Query $Query;

    public function __construct(?string $uri = null)
    {
        $this->Host  = new Host();
        $this->Query = new Query();

        if (!empty($uri)) {
            $this->Scheme = parse_url($uri, PHP_URL_SCHEME);
            if (!$this->Scheme) {
                $this->Scheme = 'http';
                $uri = $this->Scheme . '://' . $uri;
            }

            $host = parse_url($uri, PHP_URL_HOST);
            $port = parse_url($uri, PHP_URL_PORT);
            $this->Host  = new Host($host, $port);

            $this->Path = parse_url($uri, PHP_URL_PATH);
            $query = parse_url($uri, PHP_URL_QUERY);
            $this->Query = new Query($query);
        }
    }

    public function __toString(): string
    {
        $uri  = '';
        $uri .= !empty($this->Scheme) ? $this->Scheme . "://" : null;
        $uri .= $this->Host;
        $uri .= !empty($this->Path) ? $this->Path : '/';
        $uri .= !empty($this->Query->__toString()) ? '?' . $this->Query : null;

        return $uri;
    }
}
