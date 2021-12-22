<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Security\Antiforgery;

use DevNet\Core\Http\HttpContext;

class Antiforgery implements IAntiforgery
{
    private AntiforgeryOptions $Options;
    private AntiforgeryTokenStore $Store;

    public function __construct(AntiforgeryOptions $options)
    {
        if ($options->Cookie->HttpOnly === null) {
            $options->Cookie->HttpOnly = true;
        }

        $this->Options   = $options;
        $this->Generator = new AntiforgeryTokenGenerator();
        $this->Store     = new AntiforgeryTokenStore($options);
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function storeTokens(HttpContext $httpContext): AntiforgeryTokenSet
    {
        $tokens = $this->getTokens($httpContext);
        $this->Store->saveCookieToken($httpContext, $tokens->CookieToken);
        return $tokens;
    }

    public function getTokens(HttpContext $httpContext): AntiforgeryTokenSet
    {
        $tokens = $httpContext->Features->get(AntiforgeryTokenSet::class);

        if (!$tokens) {
            $tokens = new AntiforgeryTokenSet();
            $token  = $this->Store->getCookieToken($httpContext);

            if (!$token) {
                $tokens->CookieToken = $this->Generator->GenerateCookieToken()->Value;
            } else {
                $tokens->CookieToken = $token;
            }

            $tokens->FormFieldName = $this->Options->FormFieldName;
            $httpContext->Features->set($tokens);
        }

        if (!$tokens->RequestToken) {
            $cookieToken = $tokens->CookieToken;
            $tokens->RequestToken = $this->Generator->GenerateRequestToken($cookieToken)->Value;
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

        return $this->Generator->matchTokens($httpContext, $tokens);
    }
}
