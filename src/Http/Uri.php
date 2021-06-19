<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

class Uri
{
    public string $Url;
    public string $Scheme   = '';
    public string $Host     = '';
    public string $Port     = '';
    public string $Path     = '';
    public string $Query    = '';

    public function __construct(string $url = '')
    {
        if (!empty($url))
        {
            $this->Url      = $url;
            $this->Scheme   = parse_url($url, PHP_URL_SCHEME);
            $this->Host     = parse_url($url, PHP_URL_HOST);
            $this->Port     = parse_url($url, PHP_URL_PORT);
            $this->Path     = parse_url($url, PHP_URL_PATH);
            $this->Query    = parse_url($url, PHP_URL_QUERY);
        } 
        else 
        {
            // Scheme
            $https = !isset($_SERVER['HTTPS']) ? 'off' : $_SERVER['HTTPS'];
            $this->Scheme = $https == 'off' ? 'http' : 'https';

            // Host
            if (isset($_SERVER['HTTP_HOST']))
            {
                $hostFragments = explode(':', $_SERVER['HTTP_HOST']);
                $this->Host = $hostFragments[0];
            }

            // Port
            $this->Port = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ($this->Scheme == 'https' ? '443' : '80');

            // Path
            if (isset($_SERVER['REQUEST_URI']))
            {
                $uriFragments = explode('?', $_SERVER['REQUEST_URI']);
                $this->Path = $uriFragments[0];
            }
 
            // Query string
            if (isset($_SERVER['QUERY_STRING']))
            {
                $this->Query = $_SERVER['QUERY_STRING'];
            }

            $port = $this->Port != '80' && $this->Port != '' ? ":".$this->Port : '';
            $query = !empty($this->Query) ? '?'.$this->Query : '';

            $this->Url = $this->Scheme."://".$this->Host.$port.$this->Path.$query;
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __toString()
    {
        return $this->Url;
    }
}
