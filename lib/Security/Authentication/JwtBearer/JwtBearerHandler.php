<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\JwtBearer;

use DevNet\System\Tweak;
use DevNet\Web\Http\HttpContext;
use DevNet\Web\Security\Authentication\AuthenticationResult;
use DevNet\Web\Security\Authentication\IAuthenticationHandler;
use DevNet\Web\Security\Tokens\Jwt\JwtSecurityTokenHandler;
use Exception;

class JwtBearerHandler implements IAuthenticationHandler
{
    use Tweak;

    private HttpContext $HttpContext;
    private JwtBearerOptions $options;
    private JwtSecurityTokenHandler $handler;

    public function __construct(HttpContext $httpContext, JwtBearerOptions $options)
    {
        $this->httpContext = $httpContext;
        $this->options = $options;
        $this->handler = new JwtSecurityTokenHandler();
    }

    public function get_Options(): JwtBearerOptions
    {
        return $this->options;
    }

    public function readToken(): string
    {
        $headers = $this->httpContext->Request->Headers->getValues('Authorization');
        if (!$headers) {
            throw new Exception("The request is missing the authorization header!");
        }

        if (!preg_match("/^Bearer\s+(.*)$/", $headers[0], $matches)) {
            throw new Exception("Incorrect authentication header scheme!");
        }

        return $matches[1];
    }

    public function authenticate(): AuthenticationResult
    {
        try {
            $token = $this->readToken();
            $jwtToken = $this->handler->validateToken($token, $this->SecurityKey, $this->Options->Issuer, $this->Options->Audiance);
            return new AuthenticationResult($jwtToken->Payload->Claims);
        } catch (\Throwable $exception) {
            return new AuthenticationResult($exception);
        }
    }
}
