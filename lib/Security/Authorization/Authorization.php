<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\Web\Security\Claims\ClaimsIdentity;

class Authorization implements IAuthorization
{
    private AuthorizationOptions $options;

    public function __construct(AuthorizationOptions $options)
    {
        $this->options = $options;
    }

    public function authorize(ClaimsIdentity $user, ?string $policyName = null): AuthorizationResult
    {
        if ($policyName == null) {
            $policyName = "Authentication";
        }

        $policy = $this->options->getPolicy($policyName);

        if (!$policy) {
            throw new AuthorizationException("Undefined Policy: {$policyName}");
        }

        $requirements = $policy->Requirements;
        $context = new AuthorizationContext($requirements, $user);

        foreach ($requirements as $requirement) {
            $requirement->getHandler()->handle($context)->wait();
        }

        return $context->getResult();
    }
}
