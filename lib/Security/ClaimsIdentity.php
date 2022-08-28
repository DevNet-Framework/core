<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security;

use Closure;
use DevNet\System\ObjectTrait;

class ClaimsIdentity
{
    use ObjectTrait;

    private ?string $authenticationType;
    private array $claims = [];

    public function __construct(string $authenticationType = null, array $claims = [])
    {
        $this->authenticationType = $authenticationType;
        $this->claims = $claims;
    }

    public function get_AuthenticationType(): ?string
    {
        return $this->authenticationType;
    }

    public function get_Claims(): array
    {
        return $this->claims;
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticationType ? true : false;
    }

    public function addClaim(Claim $claim)
    {
        $this->claims[spl_object_id($claim)] = $claim;
    }

    public function removeClaim(Claim $claim): bool
    {
        if (isset($this->claims[spl_object_id($claim)])) {
            unset($this->claims[spl_object_id($claim)]);
            return true;
        }

        return false;
    }

    public function hasClaim(string $type, string $value): bool
    {
        foreach ($this->claims as $claim) {
            if ($claim->Type == $type && $claim->Value == $value) {
                return true;
            }
        }

        return false;
    }

    public function findClaim(Closure $predecate): ?Claim
    {
        foreach ($this->claims as $claim) {
            if ($predecate($claim)) {
                return $claim;
            }
        }

        return null;
    }

    public function findClaims(Closure $predecate): array
    {
        $claims = [];

        foreach ($this->claims as $claim) {
            if ($predecate($claim)) {
                $claims[] = $claim;
            }
        }

        return $claims;
    }

    public function getObjectData(): string
    {
        return serialize($this);
    }
}
