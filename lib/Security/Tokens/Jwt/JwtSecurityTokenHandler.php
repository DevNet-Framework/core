<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Tokens\Jwt;

use DevNet\Web\Security\Claims\Claim;
use DevNet\Web\Security\Claims\ClaimsIdentity;
use DevNet\Web\Security\Tokens\Base64UrlEncoder;
use DevNet\Web\Security\Tokens\Jwt\JwtSecurityToken;
use DateTime;

class JwtSecurityTokenHandler
{
    public function createToken(array $payload, string $algorithm = 'HS256', ?DateTime $expires = null): JwtSecurityToken
    {
        $claims = new ClaimsIdentity();
        foreach ($payload as $key => $values) {
            if (is_array($values)) {
                foreach ($values as $value) {
                    $claims->addClaim(new Claim($key, $value));
                }
            } else {
                $claims->addClaim(new Claim($key, $values));
            }
        }

        return new JwtSecurityToken($claims, $algorithm, $expires);
    }

    /**
     * Serializes nstance of JwtSecurityToken to a signed string token.
     */
    public function writeToken(JwtSecurityToken $token, string $securityKey): string
    {
        switch ($token->Header->Alg) {
            case 'HS256':
                $signature = hash_hmac('sha256', $token->toString(), $securityKey, true);
                break;
            case 'HS384':
                $signature = hash_hmac('sha834', $token->toString(), $securityKey, true);
                break;
            case 'HS512':
                $signature = hash_hmac('sha512', $token->toString(), $securityKey, true);
                break;
            default:
                throw new JwtException("Insupported Encription Algorithm!");
                break;
        }

        $signature = Base64UrlEncoder::encode($signature);
        return $token->toString() . '.' . $signature;
    }

    /**
     * Converts a JWT string into an instance of JwtSecurityToken without validation
     */
    public function readToken(string $token): JwtSecurityToken
    {
        $segments = explode('.', $token);
        if (count($segments) != 3) {
            throw new JwtException("Error Processing Request");
        }

        $header    = Base64UrlEncoder::decode($segments[0]);
        $header    = json_decode($header, true);
        $payload   = Base64UrlEncoder::decode($segments[1]);
        $payload   = json_decode($payload, true);
        $algorithm = $header['alg'] ?? '';

        return $this->createToken($payload, $algorithm);
    }

    /**
     * Reads and validates JWT string
     */
    public function validateToken(string $token, string $securityKey, ?string $issuer = null, ?string $audience = null): JwtSecurityToken
    {
        $segments = explode('.', $token);
        $token = $this->readToken($token);

        switch ($token->Header->Alg) {
            case 'HS256':
                $signature = hash_hmac('sha256', $segments[0] . "." . $segments[1], $securityKey, true);
                break;
            case 'HS384':
                $signature = hash_hmac('sha834', $segments[0] . "." . $segments[1], $securityKey, true);
                break;
            case 'HS512':
                $signature = hash_hmac('sha512', $segments[0] . "." . $segments[1], $securityKey, true);
                break;
            default:
                throw new JwtException("Insupported Encription Algorithm!");
                break;
        }

        $signature = Base64UrlEncoder::encode($signature);

        if (!hash_equals($signature, $segments[2])) {
            throw new JwtException("Invalide JWT signature!", 1);
        }

        $exp = $token->Payload->Claims->findClaim(fn ($claim) => $claim->Type == 'exp');
        if ($exp != null && $exp->Value <= time()) {
            throw new JwtException("Expired JWT token!", 2);
        }

        if ($issuer) {
            $iss = $token->Payload->Claims->findClaim(fn ($claim) => $claim->Type == 'iss');
            if ($iss != null && $iss->Value != $issuer) {
                throw new JwtException("Invalide JWT Issuer!", 3);
            }
        }

        if ($audience) {
            $aud = $token->Payload->Claims->findClaim(fn ($claim) => $claim->Type == 'aud');
            if ($aud != null && $aud->Value != $audience) {
                throw new JwtException("Invalide JWT Audience!", 4);
            }
        }

        return $token;
    }
}
