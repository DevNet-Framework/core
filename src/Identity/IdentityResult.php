<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Identity;

use DevNet\Data\Entity\IEntity;

class IdentityResult
{
    const Succeeded  = 1;
    const NoAction   = 0;
    const Failed     = -1;
    const NotAllowed = -2;

    private int $Status = 0;

    public function __construct(int $code = 0)
    {
        $this->Status = $code;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function isSucceeded() : bool
    {
        return $this->Status == 1 ? true : false;
    }
}
