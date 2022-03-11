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

class User implements IEntity
{
    public int $Id;
    public string $Username;
    public string $Password;

    public IList $UserRole;

    public function __construct(string $username = null)
    {
        if ($username) {
            $this->Username = $username;
        }
    }
}
