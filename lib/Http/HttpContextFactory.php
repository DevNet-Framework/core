<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

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
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $uri = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $uri .= isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $uri .= isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? strstr($_SERVER['REQUEST_URI'] . '?', '?', true) : '/';
        $uri .= isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : null;

        try {
            $headers = getallheaders();
        } catch (\Throwable $th) {
            $headers = [];
        }

        $files = [];
        foreach ($_FILES as $name => $upload) {
            foreach ($upload as $key => $info) {
                if (is_array($info)) {
                    foreach ($info as $index => $value) {
                        $files[$name][$index][$key] = $value;
                    }
                } else {
                    $files[$name][0][$key] = $info;
                }
            }
        }

        $fileCollection = new FileCollection();
        foreach ($files as $name => $upload) {
            foreach ($upload as $file) {
                $formFile = new FormFile($file['name'], $file['type'], $file['tmp_name'], $file['size'], $file['error']);
                $fileCollection->addFile($name, $formFile);
            }
        }

        $body    = new Stream('php://input', 'r');
        $form    = new Form($_POST, $fileCollection);
        $request = new HttpRequest($method, $uri, $headers, $body, $form);
        return $request;
    }

    public static function createResponse(): HttpResponse
    {
        $body     = new Stream('php://temp', 'r+');
        $response = new HttpResponse($body);
        return $response;
    }
}
