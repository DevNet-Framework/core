<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Entity\IEntity;

class UserRole implements IEntity
{
    public int $UserId;
    public int $RoleId;

    public User $User;
    public Role $Role;
}
