<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Routing\Internal;

/**
 * This is an internal API that supports the Router system infrastructure, It may be changed or removed without notice in
 * any release, Because is not subjected to be standard as public APIs.
 */
class RouteMatcher
{
    static function matchUrl(string $pattern, string $urlPath): ?array
    {
        $pattern = strtolower($pattern);
        $urlPath = strtolower($urlPath);
        $segments = substr_count($pattern, '/') - substr_count($urlPath, '/');
        if ($segments >= 0) {
            $urlPath = $urlPath . str_repeat('/', $segments);
            if (preg_match('%^' . $pattern . '$%', $urlPath, $matches)) {
                return $matches;
            }
        }

        return null;
    }

    static function matchMethod(string $httpMethod, ?string $verb): bool
    {
        $httpMethod = strtoupper($httpMethod);
        $verb = strtoupper((string) $verb);

        if (!$verb || $verb == $httpMethod) {
            return true;
        }

        return false;
    }
}
