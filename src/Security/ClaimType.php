<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\DevNet\Security;

class ClaimType
{
    const Anonymous     = "Anonymous";
    const Name          = "Name";
    const BirthDate     = "BirthDate";
    const Gender        = "Gender";
    const SerialNumber  = "SerialNumber";
    const PhoneNumber   = "PhoneNumber";
    const MobileNumber  = "MobileNumber";
    const Email         = "Email";
    const Address       = "Address";
    const City          = "City";
    const State         = "State";
    const Country       = "Country";
    const PostalCode    = "PostalCode";
    const Webpage       = "Webpage";
    const Role          = "Role";
}