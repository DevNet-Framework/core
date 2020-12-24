<?php

namespace Application\Controllers;

use Artister\Web\Mvc\Controller;
use Artister\Web\Mvc\IActionResult;

class HomeController extends Controller
{
    public function index() : IActionResult
    {
        return $this->view('home/index');
    }
}