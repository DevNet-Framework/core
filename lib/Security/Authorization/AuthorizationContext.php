<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\System\Tweak;
use DevNet\Web\Security\Claims\ClaimsIdentity;

class AuthorizationContext
{
    use Tweak;

    private array $requirements;
    private ?ClaimsIdentity $user;
    private bool $failCalled    = false;
    private bool $successCalled = false;

    public function __construct(array $requirements = [], ?ClaimsIdentity $user = null)
    {
        $this->user = $user;
        foreach ($requirements as $requirement) {
            $this->requirements[spl_object_id($requirement)] = $requirement;
        }
    }

    public function get_Requirements(): array
    {
        return $this->requirements;
    }

    public function get_User(): ?ClaimsIdentity
    {
        return $this->user;
    }

    public function fail()
    {
        $this->failCalled = true;
    }

    public function success(IAuthorizationRequirement $requirement)
    {
        $this->successCalled = true;
        if (isset($this->requirements[spl_object_id($requirement)])) {
            unset($this->requirements[spl_object_id($requirement)]);
        }
    }

    public function getResult(): AuthorizationResult
    {
        $status = 0;

        if (!$this->failCalled && $this->successCalled && !$this->requirements) {
            $status = 1;
        } else if ($this->failCalled) {
            $status = -1;
        }

        return new AuthorizationResult($status, $this->requirements);
    }
}
