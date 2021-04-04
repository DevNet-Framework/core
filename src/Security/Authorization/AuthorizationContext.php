<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Security\Authorization;

use DevNet\Web\Security\ClaimsPrincipal;

class AuthorizationContext
{
    private array $Requirements;
    private ?ClaimsPrincipal $User;
    private bool $FailCalled    = false;
    private bool $SuccessCalled = false;

    public function __construct(array $requirements = [], ?ClaimsPrincipal $user = null)
    {
        $this->User         = $user;
        $this->Requirements = $requirements;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function fail()
    {
        $this->FailCalled = true;
    }

    public function success(IAuthorizationRequirement $requirement)
    {
        $this->SuccessCalled = true;
        if (isset($this->Requirements[spl_object_id($requirement)])) {
            unset($this->Requirements[spl_object_id($requirement)]);
        }
    }

    public function getResult() : AuthorizationResult
    {
        $status = 0;

        if (!$this->FailCalled && $this->SuccessCalled && !$this->Requirements) {
            $status = 1;
        }
        else if ($this->FailCalled) {
            $status = -1;
        }

        return new AuthorizationResult($status, $this->Requirements);
    }
}
