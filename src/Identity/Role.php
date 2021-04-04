<?php declare(strict_types = 1);
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
    protected int $Id;
    protected string $Name;

    protected IList $UserRole;

    public function __construct(string $roleName = null)
    {
        if ($roleName)
        {
            $this->Name = $roleName;
        }
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
        $this->$name = $value;
    }
}
