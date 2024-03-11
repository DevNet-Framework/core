<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Csrf;

use DevNet\System\PropertyTrait;

class AntiForgery implements IAntiForgery
{
    use PropertyTrait;

    private AntiForgeryOptions $options;
    private AntiForgeryTokenGenerator $generator;
    private AntiForgeryTokenStore $store;

    public function __construct(AntiForgeryOptions $options)
    {
        $this->options   = $options;
        $this->generator = new AntiForgeryTokenGenerator();
        $this->store     = new AntiForgeryTokenStore($options);
    }

    public function get_Options(): AntiForgeryOptions
    {
        return $this->options;
    }

    public function getToken(): AntiForgeryToken
    {
        $token = $this->store->getCookieToken();
        if ($token) {
            return $token;
        }

        $token = new AntiForgeryToken();
        $this->store->saveCookieToken($token);
        return $token;
    }

    public function validateToken(string $token): bool
    {
        if ($this->getToken() == $token) {
            return true;
        }

        return false;
    }
}
