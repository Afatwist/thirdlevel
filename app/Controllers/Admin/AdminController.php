<?php

namespace App\Controllers\Admin;

class AdminController extends MainAdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo $this->templates->render('admin/index');
	}
}
