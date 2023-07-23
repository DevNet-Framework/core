<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\Tweak;
use DevNet\Web\Http\HttpContext;

class Antiforgery implements IAntiforgery
{
    use Tweak;

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
    
    public function get_Store(): AntiforgeryTokenStore
    {
        return $this->store;
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
