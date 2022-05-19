<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Antiforgery;

use DevNet\System\Exceptions\PropertyException;
use DevNet\Web\Http\HttpContext;

class Antiforgery implements IAntiforgery
{
    private AntiforgeryOptions $options;
    private AntiforgeryTokenStore $store;

    public function __get(string $name)
    {
        if ($name == 'Options') {
            return $this->options;
        }

        if ($name == 'Store') {
            return $this->store;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(AntiforgeryOptions $options)
    {
        if ($options->Cookie->HttpOnly === null) {
            $options->Cookie->HttpOnly = true;
        }

        $this->options   = $options;
        $this->generator = new AntiforgeryTokenGenerator();
        $this->store     = new AntiforgeryTokenStore($options);
    }

    public function storeTokens(HttpContext $httpContext): AntiforgeryTokenSet
    {
        $tokens = $this->getTokens($httpContext);
        $this->store->saveCookieToken($httpContext, $tokens->CookieToken);
        return $tokens;
    }

    public function getTokens(HttpContext $httpContext): AntiforgeryTokenSet
    {
        $tokens = $httpContext->Features->get(AntiforgeryTokenSet::class);
        if (!$tokens) {
            $tokens = new AntiforgeryTokenSet();
            $token  = $this->store->getCookieToken($httpContext);

            if (!$token) {
                $tokens->CookieToken = $this->generator->GenerateCookieToken()->Value;
            } else {
                $tokens->CookieToken = $token;
            }

            $tokens->FormFieldName = $this->options->FormFieldName;
            $httpContext->Features->set($tokens);
        }

        if (!$tokens->RequestToken) {
            $cookieToken = $tokens->CookieToken;
            $tokens->RequestToken = $this->generator->GenerateRequestToken($cookieToken)->Value;
        }

        return $tokens;
    }

    public function validateTokens(HttpContext $httpContext): bool
    {
        $method = $httpContext->Request->Method;
        if (!in_array($method, ["POST", "PUT", "UPDATE", "DELETE"])) {
            return true;
        }

        $tokens = $this->getTokens($httpContext);
        return $this->generator->matchTokens($httpContext, $tokens);
    }
}
