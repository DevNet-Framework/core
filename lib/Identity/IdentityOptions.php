<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) 2018-2020 Mohammed Moussaoui
 * @license     MIT License
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Identity;

class IdentityOptions
{
    public string $UsernameFormat = "[a-zA-Z0-9_.]";
    public string $PasswordFormat = "(?=.*?[A-Z])(?=.*?[0-9]).{8,}";
}
