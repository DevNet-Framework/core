<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Claims;

use Closure;

class ClaimsPrincipal
{
    private array $identities = [];

    public function __construct(ClaimsIdentity $identity = null)
    {
        if ($identity != null) {
            $this->identities[$identity->AuthenticationType] = $identity;
        }
    }

    public function addIdentity(ClaimsIdentity $identity)
    {
        $this->identities[$identity->AuthenticationType] = $identity;
    }

    public function findClaim(Closure $predecate): ?Claim
    {
        foreach ($this->identities as $identity) {
            $claim = $identity->findClaim($predecate);
            if ($claim != null) {
                return $claim;
            }
        }

        return null;
    }

    public function findClaims(Closure $predecate): array
    {
        $claims = [];
        foreach ($this->identities as $identity) {
            foreach ($identity->findClaims($predecate) as $claim) {
                $claims[] = $claim;
            }
        }

        return $claims;
    }

    public function isAuthenticated()
    {
        foreach ($this->identities as $identity) {
            if ($identity->isAuthenticated()) {
                return true;
            }

            return false;
        }
    }

    public function IsInRole(string $role): bool
    {
        foreach ($this->identities as $identity) {
            if ($identity->hasClaim(ClaimType::Role, $role)) {
                return true;
            }
        }

        return false;
    }

    public function object(): string
    {
        return serialize($this);
    }
}
