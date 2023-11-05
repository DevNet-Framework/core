<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DevNet\System\IO\FileAccess;
use DevNet\System\IO\FileMode;
use DevNet\System\IO\FileStream;

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

        $fileCollection = new FormFileCollection();
        foreach ($files as $name => $upload) {
            foreach ($upload as $file) {
                $formFile = new FormFile($file['name'], $file['type'], $file['tmp_name'], $file['size'], $file['error']);
                $fileCollection->addFile($name, $formFile);
            }
        }

        $headers = new Headers($headers);
        $body    = new FileStream('php://input', FileMode::Open, FileAccess::Read);
        $form    = new Form($_POST, $fileCollection);
        $request = new HttpRequest($method, $uri, $headers, $body, $form);

        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $request->setProtocol($_SERVER['SERVER_PROTOCOL']);
        }

        return $request;
    }

    public static function createResponse(): HttpResponse
    {
        $response = new HttpResponse();
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $response->setProtocol($_SERVER['SERVER_PROTOCOL']);
        }

        return $response;
    }
}
