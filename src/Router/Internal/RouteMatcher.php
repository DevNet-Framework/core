<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Router\Internal;

/**
 * This is an internal API that supports the Router system infrastructure, It may be changed or removed without notice in
 * any release, Becasue is not subjected to be standard as public APIs.
 */
class RouteMatcher
{
    static function matchUrl($pattern, $urlPath) : ?array
    {
        $segments = substr_count($pattern, '/') - substr_count($urlPath, '/');
        if ($segments >= 0)
        {
            $urlPath = $urlPath . str_repeat('/', $segments);
            if (preg_match('%^'.$pattern.'$%', $urlPath, $matches))
            {
                return $matches;
            }
        }

        return null;
    }

    static function matchMethod($httpMethod, $verb) : bool
    {
        $httpMethod = strtoupper($httpMethod);
        $verb = strtoupper($verb);
        
        if ($verb == $httpMethod || $verb == 'ANY' || $verb == '')
        {
            return true;
        }

        return false;
    }
}
