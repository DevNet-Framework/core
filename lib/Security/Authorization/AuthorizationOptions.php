<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Collections\Enumerator;
use DevNet\System\Collections\IEnumerable;
use Closure;

class AuthorizationOptions implements IEnumerable
{
    private array $policies = [];

    public function __construct()
    {
        $this->policies["Authentication"] = new AuthorizationPolicy("Authentication", [new AuthenticationRequirement()]);
    }

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
