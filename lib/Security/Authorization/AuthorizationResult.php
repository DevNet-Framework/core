<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Exceptions\PropertyException;

class AuthorizationResult
{
    private int $status;
    private array $failedRequirements;

    public function __get(string $name)
    {
        if ($name == 'Status') {
            return $this->status;
        }

        if ($name == 'FailedRequirements') {
            return $this->failedRequirements;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(int $status = 0, array $failedRequirements = [])
    {
        $this->status = $status;
        $this->failedRequirements = $failedRequirements;
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
