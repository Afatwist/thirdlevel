<?php

namespace App\Controllers;

class HomeController extends MainController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo $this->templates->render('home/index');
	}

	public function about()
	{
		echo $this->templates->render('home/about');
	}

		public function contacts()
	{
		echo $this->templates->render('home/contacts');
	}
}
