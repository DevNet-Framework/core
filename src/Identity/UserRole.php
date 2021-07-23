<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Core\Identity;

use DevNet\Entity\IEntity;

class UserRole implements IEntity
{
    protected int $UserId;
    protected int $RoleId;

    protected User $User;
    protected Role $Role;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}
