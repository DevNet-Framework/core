<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Identity;

use Artister\Data\Entity\IEntity;

class UserRole implements IEntity
{
    private int $UserId;
    private int $RoleId;

    private User $User;
    private Role $Role;

    public function __get(string $name)
    {
        return $this->$name;
    }
}