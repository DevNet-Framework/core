<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\DevNet\Entity\IEntity;

class IdentityResult
{
    private int $Error = 0;

    public function __construct(int $error = 0)
    {
        $this->Error = $error;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function isSucceeded() : bool
    {
        return $this->Error == 0 ? true : false;
    }
}