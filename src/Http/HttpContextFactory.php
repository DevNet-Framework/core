<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Http;

class HttpContextFactory
{
    public $Options;

    static public function create() : HttpContext
    {
        $request = new Request();
        $response = new Response();
        return new HttpContext($request, $response);
    }
}
