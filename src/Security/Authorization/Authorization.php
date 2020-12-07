<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authorization;

use Artister\DevNet\Security\ClaimsPrincipal;

class Authorization
{
    private AuthorizationOptions $Options;

    public function __construct(AuthorizationOptions $options)
    {
        $this->Options = $options;
    }

    public function Authorize(string $policyName, ?ClaimsPrincipal $user) : AuthorizationResult
    {
        $policy = $this->Options->getPolicy($policyName);

        if (!$policy) {
            throw new \Exception("Policy {$policyName} dose not exist");
        }

        $handlers = $requirements = $policy->Requirements;
        $context = new AuthorizationContext($requirements, $user);
        
        foreach ($handlers as $handler) {
            $handler->handle($context);
        }

        return $context->getResult();
    }
}