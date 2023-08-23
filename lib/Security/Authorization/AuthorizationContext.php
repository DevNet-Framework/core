<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Tweak;
use DevNet\Web\Security\Claims\ClaimsIdentity;

class AuthorizationContext
{
    use Tweak;

    private ?ClaimsIdentity $user = null;
    private array $requirements = [];
    private array $pendingRequirements = [];
    private array $failedRequirements = [];

    public function __construct(array $requirements = [], ?ClaimsIdentity $user = null)
    {
        $this->user = $user;
        $this->requirements = $requirements;
        foreach ($requirements as $requirement) {
            $this->pendingRequirements[spl_object_id($requirement)] = $requirement;
        }
    }

    public function get_Requirements(): array
    {
        return $this->requirements;
    }

    public function get_FailedRequirements(): array
    {
        return $this->requirements;
    }

    public function get_PendingRequirements(): array
    {
        return $this->requirements;
    }

    public function get_User(): ?ClaimsIdentity
    {
        return $this->user;
    }

    public function fail(?IAuthorizationRequirement $requirement = null): void
    {
        if (isset($this->pendingRequirements[spl_object_id($requirement)])) {
            unset($this->pendingRequirements[spl_object_id($requirement)]);
            $this->failedRequirements[] = $requirement;
        }
    }

    public function succeed(IAuthorizationRequirement $requirement): void
    {
        if (isset($this->pendingRequirements[spl_object_id($requirement)])) {
            unset($this->pendingRequirements[spl_object_id($requirement)]);
        }
    }

    public function getResult(): AuthorizationResult
    {
        $failedRequirements = $this->failedRequirements;
        foreach ($this->pendingRequirements as $requirement) {
            $failedRequirements[] = $requirement;
        }
        
        // Failure result
        if ($failedRequirements) {
            return new AuthorizationResult($failedRequirements);
        }

        // Succeeded result.
        return new AuthorizationResult();
    }
}
