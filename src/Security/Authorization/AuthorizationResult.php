<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security\Authorization;

class AuthorizationResult
{
    private int $Status;
    private array $FailedRequirements;

    public function __construct(int $status = 0, array $failedRequirements = [])
    {
        $this->Status               = $status;
        $this->FailedRequirements   = $failedRequirements;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function isSucceeded() : bool
    {
        return $this->Status == 1 ? true : false;
    }

    public function isFailed() : bool
    {
        return $this->Status == -1 ? true : false;
    }
}