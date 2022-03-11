<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

use DevNet\Entity\IEntity;
use DevNet\System\Collections\IList;

class Role implements IEntity
{
    public int $Id;
    public string $Name;

    public IList $UserRole;

    public function __construct(string $roleName = null)
    {
        if ($roleName) {
            $this->Name = $roleName;
        }
    }
}
