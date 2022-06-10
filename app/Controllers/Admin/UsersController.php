<?php

namespace App\Controllers\Admin;

use App\Classes\Activity;
use App\Classes\Roles;
use App\Classes\Status;

use App\Classes\Validate;
use App\Classes\ImageManager;
use App\Classes\UserManager;

use Exception;

class UsersController extends MainAdminController
{

	private $userManager;
	private $imageManager;

	public function __construct(UserManager $userManager, ImageManager $imageManager)
	{
		parent::__construct();
		$this->userManager = $userManager;
		$this->imageManager = $imageManager;
	}

	public function index()
	{
		$users = $this->userManager->getAllUsers();
		$usersForDeleting = $this->userManager->userForDeleting();
		echo $this->templates->render('admin\users\index', ['users' => $users, 'usersForDeleting' => $usersForDeleting]);
	}
	// создание профиля пользователя
	public function create()
	{
		echo $this->templates->render('admin\users\create', ['token' => $this->validate->tokenGenerate(), 'roles' => Roles::getRoleList(), 'activities' => Activity::getActivityList(), 'statuses' => Status::getStatusList()]);
	}
	public function createAction()
	{
		try {
			// валидация
			$this->validate->POST('EASY');
			$this->validate->FILES('image');

			// выполнение регистрации
			$userId = $this->userManager->createUser($_POST);
			$avatarName = $this->imageManager->avatarUpdate($_FILES['avatar']);
			$this->userManager->editUserInfo($userId, ['avatar' => $avatarName]);

			// вывод сообщений и ошибок
			flash()->success('New user was created with the ID ' . $userId);
			if ($_POST['send_email'] === 'true') {
				flash()->success('Confirmation link has been sent to your mailbox');
			}
			if ($avatarName != null) flash()->success('Avatar was upload!');
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			flash()->error($e->getMessage());
		} catch (\App\Exceptions\Validate\IncorrectTokenException $e) {
			flash()->error($e->getMessage());
		} catch (Exception $e) {
			flash()->warning($e->getMessage());
		}

		if (flash()->hasMessages('success')) redirectTo('/admin');

		redirectBack();
	}

	// автоматическое создание пользователя 
	public function createFakerUserAction()
	{
		$fakerUser = $this->myFaker->fakerUser();
		$fakerAvatar = $this->myFaker->fakerAvatar();
		$avatarName = $this->imageManager->saveFakerAvatar($fakerAvatar);
		try {
			// выполнение регистрации
			$userId = $this->userManager->createUser($fakerUser);

			$this->userManager->editUserInfo($userId, ['avatar' => $avatarName]);

			// вывод сообщений и ошибок
			flash()->success('New user was created with the ID ' . $userId . 'Пароль:  ' . $fakerUser['password']);
		} catch (Exception $e) {
			flash()->warning($e->getMessage());
		}

		redirectTo('/admin/users/edit/id=' . $userId);
	}

	// редактирование профиля
	public function edit($id)
	{
		$user = $this->userManager->getUserByID($id);

		echo $this->templates->render('admin\users\edit', ['user' => $user]);
	}

	public function editAction($id)
	{
		$user = $this->userManager->getUserByID($id);

		try {
			// валидация
			$this->validate->FILES('image');
			if (empty($_POST['password'])) {
				$this->validate->POST('NULL');
			} else {
				$this->validate->POST('EASY');
			}
			//редактирование регистрационных данных
			$regData = [
				'password' => $_POST['password'],
				'email' => $_POST['email'],
				'username' => $_POST['username']
			];
			$this->userManager->editUserRegDataByAdmin($id, $regData);

			// изменение роли и статуса
			$this->userManager->statusAndRoleChanger($id, $_POST['status'], $_POST['roles_mask']);

			// выполнение редактирования в моей таблице
			$avatarName = $this->imageManager->avatarUpdate($_FILES['avatar'], $user->avatar);
			$myData = $_POST['myInfo'];
			$myData['user_id'] = $id;
			$myData['avatar'] = $avatarName;
			$this->userManager->editUserInfo($id, $myData);

			// вывод сообщений и ошибок
			flash()->success('User ' . $id . ' was edited');
			if ($_POST['send_email'] === 'true') {
				flash()->success('Confirmation link has been sent to user`s mailbox');
			}
		} catch (Exception $e) {
			flash()->warning($e->getMessage());
		}
		redirectBack();
	}

	// удаление профиля
	public function deleteAction($id)
	{
		$avatar = $this->userManager->getUserByID($id)->avatar;
		$this->imageManager->deleteAvatar($avatar);
		$this->userManager->deleteUserById($id);
		flash()->success('User ' . $id . ' Was DELETING');
		redirectBack();
	}
}
