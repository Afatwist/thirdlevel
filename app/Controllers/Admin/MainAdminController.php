<?php

namespace App\Controllers\Admin;

use App\Classes\MyFaker;
use App\Controllers\MainController;

class MainAdminController extends MainController
{
	protected $myFaker;
	protected function __construct()
	{
		parent::__construct();
		
		$this->myFaker = new MyFaker;

		$_SESSION['auth_roles'] = 1; // для тестирования

		if (!isAdmin()) {
			flash()->error('For Admin only!');
			redirectTo('/index');
		}
	}
}
