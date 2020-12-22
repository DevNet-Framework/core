<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/artister
 */

namespace Artister\Web\Mvc;

use Artister\Web\Mvc\Binder\IValueProvider;
use Artister\Web\Http\HttpContext;

class ActionContext
{
    private ActionDescriptor $ActionDescriptor;
    private HttpContext $HttpContext;
    private IValueProvider $ValueProvider;
    private array $ActionFilters;

    public function __construct(ActionDescriptor $actionDescriptor, HttpContext $httpConext, IValueProvider $provider)
    {
        $this->ActionDescriptor = $actionDescriptor;
        $this->HttpContext      = $httpConext;
        $this->ValueProvider    = $provider;
    }

    /**
     * read-only for all properties.
     * @return mixed property value depend on the property type.
     */
    public function __get(string $name)
    {
        return $this->$name;
    }
}