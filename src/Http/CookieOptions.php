<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

use DateTime;

class CookieOptions
{
    public ?DateTime $Expires = null;
    public ?int $MaxAge       = null;
    public ?string $Domain    = null;
    public ?string $Path      = null;
    public ?string $SameSite  = null;
    public ?bool $HttpOnly    = null;
    public bool $Secure       = false;

    public function __construct(?DateTime $expires = null, ?string $path = null)
    {
        $this->Expires = $expires;
        $this->Path = $path;
    }

    public function __toString() : string
    {
        $options = "";
        if ($this->Expires)
        {
            $expires = $this->Expires->format("D, d-M-Y H:i:s T");
            $options .= "Expires={$expires};";
        }

        $options .= $this->Domain ? "Domain={$this->Domain};" : "";
        $options .= $this->MaxAge ? "Max-Age={$this->MaxAge};" : "";
        $options .= $this->Path ? "Path={$this->Path};" : "";
        $options .= $this->SameSite ? "SameSite={$this->SameSite};" : "";
        $options .= $this->HttpOnly ? "HttpOnly;" : "";
        $options .= $this->Secure ? "Secure;" : "";
        
        return $options;
    }
}
