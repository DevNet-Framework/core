<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Security;

use Artister\System\Type;
use Closure;

class ClaimsPrincipal
{
    public array $Identities = [];

    public function __construct(ClaimsIdentity $Identity = null)
    {
        if ($Identity != null)
        {
            $this->Identities[$Identity->AuthenticationType] = $Identity;
        }
    }

    public function addIdentity(ClaimsIdentity $Identity)
    {
        $this->Identities[$Identity->AuthenticationType] = $Identity;
    }

    public function findClaim(Closure $predecate) : ?Claim
    {
        foreach ($this->Identities as $identity)
        {
            $claim = $identity->findClaim($predecate);
            if ($claim != null)
            {
                return $claim;
            }
        }

        return null;
    }

    public function findClaims(Closure $predecate) : array
    {
        $claims = [];

        foreach ($this->Identities as $identity)
        {
            foreach ($identity->findClaims($predecate) as $claim)
            {
                $claims[] = $claim;
            }
        }

        return $claims;
    }

    public function isAuthenticated()
    {
        foreach ($this->Identities as $identity)
        {
            if ($identity->isAuthenticated())
            {
                return true;
            }

            return false;
        }
    }

    public function IsInRole(string $role) : bool
    {
        foreach ($this->Identities as $identity)
        {
            if ($identity->hasClaim(ClaimType::Role, $role))
            {
                return true;
            }
        }

        return false;
    }

    public function object() : string
    {
        return serialize($this);
    }

}