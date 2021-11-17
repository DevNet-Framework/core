<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\IO\Stream;

class HttpContextFactory
{
    public static function create(): HttpContext
    {
        $request = self::createRequest();
        $response = self::createResponse();
        return new HttpContext($request, $response);
    }

    public static function createRequest(): HttpRequest
    {
        $uri         = new Uri();
        $uri->Scheme = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $uri->Host   = isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) ? (explode(':', $_SERVER['HTTP_HOST']))[0] : 'localhost';
        $uri->Port   = isset($_SERVER['SERVER_PORT']) && !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ($uri->Scheme == 'https' ? '443' : '80');
        $uri->Path   = isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? strstr($_SERVER['REQUEST_URI'] . '?', '?', true) : '/';
        $uri->Query  = $_SERVER['QUERY_STRING'] ?? null;
        $method      = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        try {
            $headers = new Headers(getallheaders());
        } catch (\Throwable $th) {
            $headers = new Headers([]);
        }

        $files = [];
        foreach ($_FILES as $name => $input) {
            foreach ($input as $key => $file) {
                if (is_array($file)) {
                    foreach ($file as $index => $file) {
                        $fileCollection[$name][$index][$key] = $file;
                    }
                } else {
                    $fileCollection[$name][0][$key] = $file;
                }
            }
        }

        $fileCollection = new FileCollection();
        foreach ($files as $name => $input) {
            foreach ($input as $file) {
                $formFile = new FormFile($name, $file['name'], $file['type'], $file['size'], $file['tmp_name'], $file['error']);
                $fileCollection->add($formFile);
            }
        }

        $cookies = new Cookies($headers);
        $body    = new Stream('php://input', 'r');
        $form    = new Form($_POST, $fileCollection);
        $request = new HttpRequest($method, $uri, $headers, $cookies, $body, $form);
        return $request;
    }

    public static function createResponse(): HttpResponse
    {
        $headers  = new Headers();
        $cookies  = new Cookies($headers);
        $body     = new Stream('php://temp', 'r+');
        $response = new HttpResponse($headers, $cookies, $body);
        return $response;
    }
}
