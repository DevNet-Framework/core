<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
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
