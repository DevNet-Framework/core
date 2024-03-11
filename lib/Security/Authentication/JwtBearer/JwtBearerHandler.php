<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authentication\JwtBearer;

use DevNet\System\PropertyTrait;
use DevNet\Web\Security\Authentication\AuthenticationResult;
use DevNet\Web\Security\Authentication\IAuthenticationHandler;
use DevNet\Web\Security\Tokens\Jwt\JwtSecurityTokenHandler;
use Exception;

class JwtBearerHandler implements IAuthenticationHandler
{
    use PropertyTrait;

    private JwtBearerOptions $options;
    private JwtSecurityTokenHandler $handler;

    public function __construct(JwtBearerOptions $options)
    {
        $this->options = $options;
        $this->handler = new JwtSecurityTokenHandler();
    }

    public function get_Options(): JwtBearerOptions
    {
        return $this->options;
    }

    public function readToken(): string
    {
        $headers = getallheaders();;
        $bearerToken = $headers['Authorization'];
        if (!$bearerToken) {
            throw new Exception("The request is missing the authorization header!");
        }

        if (!preg_match("/^Bearer\s+(.*)$/", $bearerToken[0], $matches)) {
            throw new Exception("Incorrect authentication header scheme!");
        }

        return $matches[1];
    }

    public function authenticate(): AuthenticationResult
    {
        try {
            $token = $this->readToken();
            $jwtToken = $this->handler->validateToken($token, $this->SecurityKey, $this->Options->Issuer, $this->Options->Audience);
            return new AuthenticationResult($jwtToken->Payload->Claims);
        } catch (\Throwable $exception) {
            return new AuthenticationResult($exception);
        }
    }
}
