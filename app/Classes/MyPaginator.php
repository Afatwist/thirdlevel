<?php

namespace App\Classes;

use JasonGrimes\Paginator;
use App\Classes\UserManager;

class MyPaginator
{
	private $userManager;
	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	public function usersPaginator($userPerPage, $page)
	{
		$count = $this->userManager->usersCount();

		$urlPattern = '/users/paginator/page=(:num)';

		$paginator = new Paginator($count, $userPerPage, $page, $urlPattern);
		return $paginator;
	}
}
