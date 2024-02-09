<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\PropertyTrait;

class AuthorizationResult
{
    use PropertyTrait;

    private bool $isSucceeded = true;
    private array $failedRequirements = [];

    /**
     * @param array<IAuthorizationRequirement> $failedRequirements
     */
    public function __construct(array $failedRequirements = [])
    {
        if ($failedRequirements) {
            $this->isSucceeded = false;
            $this->failedRequirements = $failedRequirements;
        }
    }

    public function get_IsSucceeded(): bool
    {
        return $this->isSucceeded;
    }

    public function get_FailedRequirements(): array
    {
        return $this->failedRequirements;
    }
}
