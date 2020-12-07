<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authorization;

use Closure;

class AuthorizationOptions
{
    private array $Policies =[];

    public function __get(string $key)
    {
        return $this->$key;
    }

    public function addPolicy(string $name, Closure $configurePolicy)
    {
        $builder = new AuthorizationPolicyBuilder($name);
        $configurePolicy($builder);
        $this->Policies[$name] = $builder->build();
    }

    public function getPolicy(string $name)
    {
        return $this->Policies[$name] ?? null;
    }
}