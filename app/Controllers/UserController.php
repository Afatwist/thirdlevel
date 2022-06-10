<?php

namespace App\Controllers;

use App\Classes\MyPaginator;
use App\Classes\UserManager;


class UserController extends MainController
{
	protected $userManager;
	protected $myPaginator;

	public function __construct(UserManager $userManager, MyPaginator $myPaginator)
	{
		parent::__construct();
		$this->userManager = $userManager;
		$this->myPaginator = $myPaginator;
	}


	public function userList()
	{
		echo $this->templates->render('users/userlist', [
			'userlist' => $this->userManager->getAllUsers()
		]);
	}

	public function userListPaginator($page=1)
	{
		$userPerPage = 3;
		$showUsers = $this->userManager->usersPagination($userPerPage, $page);
		if (!$showUsers) {
			flash()->error('Произошла ошибка');
			redirectTo('/users/userlist');
		}
		$paginator = $this->myPaginator->usersPaginator($userPerPage, $page);

		echo $this->templates->render('/users/paginator', [
			'showUsers' => $showUsers, 'paginator' => $paginator
		]);
	}

	public function profile($id)
	{
		$user = $this->userManager->getUserByID($id);
		if ($user === false) {
			flash()->error('User ID not found');
			redirectTo('/uses/userlist');
		}
		echo $this->templates->render('/users/profile', ['user' => $user]);
	}

	public function registration()
	{
		echo $this->templates->render('/users/registration', []);
	}

	public function registrationAction()
	{
		try {
			// валидация
			$this->validate->POST('EASY');

			// выполнение регистрации
			$userId = $this->userManager->registration($_POST);

			// вывод сообщений и ошибок
			flash()->success('New user was created with the ID ' . $userId);
			flash()->success('Confirmation link has been sent to your mailbox');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error($e->getMessage());
		} catch (\App\Exceptions\Validate\IncorrectTokenException $e) {
			flash()->error($e->getMessage());
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}

		// переадресация
		if (flash()->hasMessages('success')) redirectTo('/users/profile/id=' . $userId);

		redirectBack();
	}

	public function email_verify($selector, $token)
	{
		try {
			$this->userManager->email_verify($selector, $token);
			flash()->success('Email address has been verified');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error('Too many requests');
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectTo('/users/login');
	}

	public function login()
	{
		echo $this->templates->render('/users/login');
	}

	public function loginAction()
	{
		try {
			$this->validate->POST('EASY');
			$userId = $this->userManager->login($_POST);
			flash()->success('User is logged in');
			redirectTo('/users/profile/id=' . $userId);
		} catch (\Delight\Auth\InvalidEmailException $e) {
			flash()->warning('Wrong email address');
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			flash()->warning('Wrong password');
		} catch (\Delight\Auth\EmailNotVerifiedException $e) {
			flash()->warning('Email not verified');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error('Too many requests');
		} catch (\App\Exceptions\Validate\IncorrectTokenException $e) {
			flash()->error($e->getMessage());
		} catch (Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectBack();
	}

	public function logout()
	{
		try {
			$this->userManager->logout();
		} catch (Exception $e) {
			flash()->error($e->getMessage());
		}
		redirectTo('/users/userlist');
	}

	public function password_forgot()
	{
		echo $this->templates->render('/users/password_forgot', []);
	}

	public function password_forgotAction()
	{
		try {
			$this->validate->POST();
			$this->userManager->password_forgot($_POST['email']);

			flash()->success('Request has been generated');
			redirectTo('/users/userlist');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error($e->getMessage());
		} catch (\App\Exceptions\Validate\IncorrectTokenException $e) {
			flash()->error($e->getMessage());
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectBack();
	}

	public function password_reset($selector, $token)
	{
		try {
			$this->userManager->password_reset($selector, $token);

			echo $this->templates->render('/users/password_change', ['selector_check' => $selector, 'token_check' => $token]);
		} catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
			die('Invalid token');
		} catch (\Delight\Auth\TokenExpiredException $e) {
			die('Token expired');
		} catch (\Delight\Auth\ResetDisabledException $e) {
			die('Password reset is disabled');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			die('Too many requests');
		}
	}

	public function password_changeAction()
	{
		try {
			$this->validate->POST();
			$this->userManager->password_change($_POST['selector_check'], $_POST['token_check'], $_POST['password']);
			echo 'Password has been reset';
		} catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
			die('Invalid token');
		} catch (\Delight\Auth\TokenExpiredException $e) {
			die('Token expired');
		} catch (\Delight\Auth\ResetDisabledException $e) {
			die('Password reset is disabled');
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			die('Invalid password');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			die('Too many requests');
		}
		redirectTo('/users/userlist');
	}
}
