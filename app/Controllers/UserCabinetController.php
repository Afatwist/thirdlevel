<?php

namespace App\Controllers;

use App\Classes\ImageManager;
use App\Classes\UserManager;


class UserCabinetController extends MainController
{
	protected $imageManager, $userManager;

	public function __construct(UserManager $userManager, ImageManager $imageManager)
	{
		parent::__construct();
		$this->userManager = $userManager;
		$this->imageManager = $imageManager;
	}

	/**
	 * проверяет залогинен ли пользователь
	 * проверяет наличие пользователя с указанным id в БД,
	 * проверяет является ли пользователь владельцем редактируемого профиля
	 * если проверки не пройдены, то редирект на главную страницу и показ сообщения об ошибке.
	 * @param int $id указанный в запросе id
	 * @return object $user в случае успешных проверок возвратит объект пользователя
	 */
	private function userPermissionCheck($id)
	{
		// является ли id числом?
		if (!is_numeric($id)) {
			flash()->error('Incorrect User ID');
			redirectTo('/users/userlist');
		}
		$id = (int) $id;

		// существует ли $_POST['id'] и совпадает ли с полученным id
		if (isset($_POST['id']) and (int)$_POST['id'] !== $id) {
			flash()->error('Incorrect User ID');
			redirectTo('/users/userlist');
		}

		// залогинен ли пользователь?
		if (!$this->userManager->isLoggedIn()) {
			flash()->error('For LoggedIn only!');
			redirectTo('/users/login');
		}

		// является ли пользователь владельцем профиля?
		if (!$this->userManager->isProfileOwner($id)) {
			flash()->error('For Profile owner only!');
			redirectTo('/users/profile/id=' . $id);
		}

		$user = $this->userManager->getUserByID($id);

		// проверка на объект
		if (!is_object($user)) {
			flash()->error('User ID not found');
			redirectTo('/users/userlist');
		}
		return $user;
	}
	// главная страница личного кабинета
	public function index($id)
	{
		$user = $this->userPermissionCheck($id);
		echo $this->templates->render('users/cabinet/index', ['user' => $user]);
	}

	// смена емейла, имени и пароля форма
	public function security($id)
	{
		$user = $this->userPermissionCheck($id);

		echo $this->templates->render('users/cabinet/security', ['user' => $user]);
	}
	// смена емейла, имени и пароля обработчик
	public function securityAction($id)
	{
		try {
			$this->validate->POST('EASY');
		} catch (\Exception $e) {
			flash()->error($e->getMessage());
			redirectTo('/users/cabinet/id=' . $id . '/security');
		}


		// проверка правильности ввода текущего пароля
		if (!$this->userManager->currentPasswordCheck($_POST['current_password'])) {
			flash()->error('current password is not true!');
			redirectTo('/users/cabinet/id=' . $id . '/security');
		}

		// смена email
		if ($_POST['email'] !== $_SESSION['auth_email']) {
			try {
				$this->userManager->userEmailChanger($_POST['email']);

				flash()->success('Email address changed!');
			} catch (\Delight\Auth\InvalidEmailException $e) {
				flash()->error('Invalid email address');
			} catch (\Delight\Auth\UserAlreadyExistsException $e) {
				flash()->error('Email address already exists');
			} catch (\Delight\Auth\EmailNotVerifiedException $e) {
				flash()->error('Account not verified');
			} catch (\Delight\Auth\NotLoggedInException $e) {
				flash()->error('Not logged in');
			} catch (\Delight\Auth\TooManyRequestsException $e) {
				flash()->error('Too many requests');
			}
		}

		// смена пароля
		if (!empty($_POST['new_password'])) {
			try {
				$this->userManager->userPasswordChanger($_POST['current_password'], $_POST['new_password']);

				flash()->success('Password has been changed');
			} catch (\Delight\Auth\NotLoggedInException $e) {
				flash()->error('Not logged in');
			} catch (\Delight\Auth\InvalidPasswordException $e) {
				flash()->error('Invalid password(s)');
			} catch (\Delight\Auth\TooManyRequestsException $e) {
				flash()->error('Too many requests');
			}
		}

		// смена имени
		if ($_POST['username'] !== $_SESSION['auth_username']) {
			$this->userManager->userUsernameChanger($id, $_POST['username']);
			flash()->success('Username has been changed');
		}
		redirectTo('/users/cabinet/id=' . $id . '/security');
	}

	// смена активности форма
	public function activity($id)
	{
		$user = $this->userPermissionCheck($id);

		echo $this->templates->render('users/cabinet/activity', ['user' => $user]);
	}
	// смена активности обработчик
	public function activityAction($id)
	{
		try {
			$this->userPermissionCheck($id);
			$this->validate->POST('EASY');
			$this->userManager->userActivityChanger($id, $_POST['new_activity']);
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
			redirectTo('/users/cabinet/id=' . $id . '/activity');
		}

		flash()->success('You have changed activity status!');
		redirectTo('/users/cabinet/id=' . $id . '/activity');
	}

	// изменение общей информации форма
	public function general($id)
	{
		$user = $this->userPermissionCheck($id);

		echo $this->templates->render('users/cabinet/general', ['user' => $user]);
	}
	// изменение общей информации обработчик
	public function generalAction($id)
	{
		try {
			$this->userPermissionCheck($id);
			$this->validate->POST('EASY');

			$data = [
				'work_place' => $_POST['work_place'],
				'phone' => $_POST['phone'],
				'address' => $_POST['address'],
			];
			$this->userManager->editUserInfo($id, $data);
			flash()->success('You have changed general info!');
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectBack();
	}

	// изменение аватарки и "о себе" форма
	public function media($id)
	{
		$user = $this->userPermissionCheck($id);

		echo $this->templates->render('users/cabinet/media', ['user' => $user]);
	}

	// изменение аватарки и "о себе" обработчик
	public function mediaAction($id)
	{
		try {
			$user = $this->userPermissionCheck($id);
			$this->validate->POST();
			$this->validate->FILES('image');
			$avatar = $this->imageManager->avatarUpdate($_FILES['avatar'], $user->avatar);
			$data = [
				'about' => $_POST['about'],
				'avatar' => $avatar
			];
			$this->userManager->editUserInfo($user->id, $data);
			flash()->success('You have changed media info!');
		} catch (\Exception $e) {
			flash()->display($e->getMessage());
		}
		redirectBack();
	}


	// Изменение контактов в социальных сетях форма
	public function social($id)
	{
		$user = $this->userPermissionCheck($id);

		echo $this->templates->render('users/cabinet/social', ['user' => $user]);
	}
	// Изменение контактов в социальных сетях обработчик
	public function socialAction($id)
	{
		try {
			$this->validate->POST();
			$user = $this->userPermissionCheck($id);

			$data = [
				'vk' => $_POST['vk'],
				'telegram' => $_POST['telegram'],
				'instagram' => $_POST['instagram'],
			];
			$this->userManager->editUserInfo($user->id, $data);
			flash()->success('You have changed social info!');
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectBack();
	}

	// Удаление профиля форма
	public function delete($id)
	{
		$user = $this->userPermissionCheck($id);
		$requestExists = $this->userManager->isExistsUserDeletingRequest($id);

		echo $this->templates->render('users/cabinet/delete', ['user' => $user, 'requestExists' => $requestExists]);
	}
	// Удаление профиля обработчик
	public function deleteAction($id)
	{
		if (!isset($_POST['sure'])) {
			flash()->error('You must be check "sure" before deleting profile');
			redirectTo('/users/cabinet/id=' . $id . '/delete');
		}

		// проверка правильности ввода текущего пароля
		if (!$this->userManager->currentPasswordCheck($_POST['current_password'])) {
			flash()->error('current password is not true!');
			redirectTo('/users/cabinet/id=' . $id . '/delete');
		}

		try {
			$this->validate->POST();
			$this->userPermissionCheck($id);
			if (!$this->userManager->userDeleteRequest($id)) {
				flash()->warning('Your request for deleting already exists!');
				redirectBack();
			}
			flash()->success('Your profile will be deleting soon!');
		} catch (\Exception $e) {

			flash()->warning($e->getMessage());
		}
		redirectTo('/users/cabinet/id=' . $id);
	}

	public function deleteActionCancel($id)
	{
		try {
			$this->validate->POST();
			$this->userPermissionCheck($id);
			$this->userManager->UserDeletingRequestCancel($id);
			flash()->info('Your request for deleting was cancelled!');
		} catch (\Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectTo('/users/cabinet/id=' . $id);
	}
}
