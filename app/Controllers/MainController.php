<?php

namespace App\Controllers;

use App\Classes\Validate;
use League\Plates\Engine;

class MainController
{
	protected $templates;
	protected $validate;

	protected function __construct()
	{
		$this->templates = getComponent(Engine::class);
		$this->validate = new Validate;
	}
}
