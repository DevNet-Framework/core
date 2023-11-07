<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\PropertyTrait;
use DevNet\Web\Http\HttpContext;

class Antiforgery implements IAntiforgery
{
    use PropertyTrait;

    private AntiforgeryOptions $options;
    private AntiforgeryTokenGenerator $generator;
    private AntiforgeryTokenStore $store;

    public function __construct(AntiforgeryOptions $options)
    {
        if ($options->Cookie->HttpOnly === null) {
            $options->Cookie->HttpOnly = true;
        }

        $this->options   = $options;
        $this->generator = new AntiforgeryTokenGenerator();
        $this->store     = new AntiforgeryTokenStore($options);
    }

    public function get_Options(): AntiforgeryOptions
    {
        return $this->options;
    }

    public function getToken(): AntiforgeryToken
    {
        $token = $this->store->getCookieToken();
        if ($token) {
            return $token;
        }

        $token = new AntiforgeryToken();
        $this->store->saveCookieToken($token);
        return $token;
    }

    public function validateToken(HttpContext $httpContext): bool
    {
        $method = $httpContext->Request->Method;
        if ($method == "GET") {
            return true;
        }

        $token = $this->getToken();

        $formToken = $httpContext->Request->Form->getValue($this->options->FieldName);
        if ($formToken == $token) {
            return true;
        }

        $headerToken = $httpContext->Request->Headers->getValues($this->options->FieldName)[0] ?? null;
        if ($headerToken == $token) {
            return true;
        }

        return false;
    }
}
