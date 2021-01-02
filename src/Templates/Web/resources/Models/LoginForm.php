<?php

namespace Application\Models;

class LoginForm
{
    private string $Username;
    private string $Password;
    private bool $Remember = false;

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value)
    {
         $this->$name = $value;
    }

    public function isValide() : bool
    {
        if (empty($this->Username) || empty($this->Password))
        {
            return false;
        }

        return true;
    }
}