<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\ObjectTrait;

class AuthorizationResult
{
    use ObjectTrait;

    private int $status;
    private array $failedRequirements;

    public function __construct(int $status = 0, array $failedRequirements = [])
    {
        $this->status = $status;
        $this->failedRequirements = $failedRequirements;
    }

    public function get_Status(): string
    {
        return $this->status;
    }

    public function get_FailedRequirements(): array
    {
        return $this->failedRequirements;
    }

    public function isSucceeded(): bool
    {
        return $this->status == 1 ? true : false;
    }

    public function isFailed(): bool
    {
        return $this->status == -1 ? true : false;
    }
}
