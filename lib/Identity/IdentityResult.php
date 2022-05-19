<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\System\Exceptions\PropertyException;

class IdentityResult
{
    public const Succeeded  = 1;
    public const NoAction   = 0;
    public const Failed     = -1;
    public const NotAllowed = -2;

    private int $status = 0;

    public function __get(string $name)
    {
        if ($name == 'Status') {
            return $this->status;
        }

        if (property_exists($this, $name)) {
            throw new PropertyException("access to private property " . get_class($this) . "::" . $name);
        }

        throw new PropertyException("access to undefined property " . get_class($this) . "::" . $name);
    }

    public function __construct(int $code = 0)
    {
        $this->status = $code;
    }

    public function isSucceeded(): bool
    {
        return $this->status == 1 ? true : false;
    }
}
