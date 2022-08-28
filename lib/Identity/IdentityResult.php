<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\System\ObjectTrait;

class IdentityResult
{
    use ObjectTrait;

    public const Succeeded  = 1;
    public const NoAction   = 0;
    public const Failed     = -1;
    public const NotAllowed = -2;

    private int $status = 0;

    public function __construct(int $code = 0)
    {
        $this->status = $code;
    }

    public function get_Status(): int
    {
        return $this->status;
    }

    public function isSucceeded(): bool
    {
        return $this->status == 1 ? true : false;
    }
}
