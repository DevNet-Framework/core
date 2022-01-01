<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router\Internal;

class RouteGenerator
{
    static function generatePath(string $urlPattern, array $params = [])
    {
        preg_match_all('%{([\w]+)(=[\w]+)?\??}%', $urlPattern, $matches);

        $matches = array_combine($matches[1], $matches[0]);

        foreach ($matches as $token => $placeholder) {
            $pattern = preg_replace('%\?%', '\?', $placeholder);
            if (isset($params[$token])) {
                $urlPattern = preg_replace('%' . $pattern . '%', $params[$token], $urlPattern);
            } else if (preg_match('%{[\w]+=([\w]+)}%', $placeholder, $value)) {
                $urlPattern = preg_replace('%' . $pattern . '%', $value[1], $urlPattern);
            } else if (preg_match('%{[\w]+\?}%', $placeholder)) {
                $urlPattern = preg_replace('%/?' . $pattern . '%', '', $urlPattern);
            } else {
                $urlPattern = 'Error';
            }
        }

        return $urlPattern;
    }
}
