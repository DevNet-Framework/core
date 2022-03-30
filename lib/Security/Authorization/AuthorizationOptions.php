<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use Closure;

class AuthorizationOptions implements IEnumerable
{
    private array $policies = [];

    public function addPolicy(string $name, Closure $configurePolicy)
    {
        $builder = new AuthorizationPolicyBuilder($name);
        $configurePolicy($builder);
        $this->policies[$name] = $builder->build();
    }

    public function getPolicy(string $name)
    {
        return $this->policies[$name] ?? null;
    }

    public function getIterator(): Enumerator
    {
        return new Enumerator($this->policies);
    }
}
