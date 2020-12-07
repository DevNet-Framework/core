<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security;

use Closure;

class ClaimsIdentity
{
    private ?string $AuthenticationType;
    private array $Claims = [];

    public function __construct(string $authenticationType = null, array $claims = [])
    {
        $this->AuthenticationType = $authenticationType;
        $this->Claims = $claims;
    }

    public function __get(string $Name)
    {
        return $this->$Name;
    }

    public function isAuthenticated() : bool
    {
        return $this->AuthenticationType ? true : false;
    }

    public function addClaim(Claim $Claim)
    {
        $this->Claims[spl_object_id($Claim)] = $Claim;
    }

    public function removeClaim(Claim $Claim) : bool
    {
        if (isset($this->Claims[spl_object_id($Claim)]))
        {
            unset($this->Claims[spl_object_id($Claim)]);
            return true;
        }

        return false;
    }

    public function hasClaim(string $type, string $value) : bool
    {
        foreach ($this->Claims as $claim)
        {
            if ($claim->Type == $type && $claim->Value == $value)
            {
                return true;
            }
        }

        return false;
    }

    public function findClaim(Closure $predecate) : ?Claim
    {
        foreach ($this->Claims as $claim)
        {
            if ($predecate($claim))
            {
                return $claim;
            }
        }

        return null;
    }

    public function findClaims(Closure $predecate) : array
    {
        $claims = [];

        foreach ($this->Claims as $claim)
        {
            if ($predecate($claim))
            {
                $claims[] = $claim;
            }
        }

        return $claims;
    }

    public function getObjectData() : string
    {
        return serialize($this);
    }

}