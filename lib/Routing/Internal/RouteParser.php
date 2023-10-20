<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing\Internal;

class RouteParser
{
    static function parseUrlPath(string $urlPath): string
    {
        // remove duplicate sashes
        $urlPath = preg_replace("%\/+%", '/', $urlPath);
        $urlPath = parse_url($urlPath, PHP_URL_PATH);

        // remove the last slash, without causing problem in case the path is empty or is only slash
        $urlPath = trim($urlPath, '/');
        $urlPath = "/" . $urlPath;

        return $urlPath;
    }

    static function parseUrlPattern(string $urlPattern): string
    {
        $urlPattern = preg_replace('%{([\w]+)}%', '(?<$1>[\w]+)', $urlPattern);
        $urlPattern = preg_replace('%{([\w]+)\?}%', '(?<$1>[\w]*)', $urlPattern);
        $urlPattern = preg_replace('%{([\w]+):(.+)}%', '(?<$1>$2)', $urlPattern);
        $urlPattern = preg_replace('%{([\w]+)=([\w]+)}%', '(?<$1_$2>[\w]*)', $urlPattern);

        return $urlPattern;
    }

    static function parsePlaceholders(array $matches): array
    {
        $routeData = [];

        foreach ($matches as $token => $value) {
            if (is_string($token)) {
                if (strrpos($token, "_")) {
                    $default = explode("_", $token);
                    if ($value == null) {
                        $routeData[$default[0]] = $default[1];
                    } else {
                        $routeData[$default[0]] = $value;
                    }
                } else {
                    $routeData[$token] = $value;
                }
            }
        }
        return $routeData;
    }
}
