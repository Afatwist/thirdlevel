<?php
// https://packagist.org/packages/delight-im/auth
namespace App\Classes;

use Delight\Auth\Auth;

class UserManager
{
	private $queryBuilder, $auth, $user;
	static private $authStatic;

	public function __construct(QueryBuilder $queryBuilder, Auth $auth)
	{
		$this->queryBuilder = $queryBuilder;
		$this->auth = $auth;
		self::$authStatic = $this->auth;
	}

	/**
	 * Регистрация пользователя 
	 * @param array $data принимает данные пользователя из формы
	 * @return int $user_id
	 * @return false
	 */
	public function registration($data)
	{
		$userId = $this->auth->register(
			$data['email'],
			$data['password'],
			$data['username'],
			function ($selector, $token) use ($data) {
				Mailer::email_verify($data['email'], $token, $selector);
			}
		);
		if (!is_numeric($userId)) return false;
		$this->queryBuilder->update('users', 'id', $userId, ['roles_mask' => Roles::USER, 'status' => Status::PENDING_REVIEW]);

		$this->queryBuilder->create('users_my_info', ['user_id' => $userId]);
		return $userId;
	}

	/** 
	 * создание пользователя админом
	 * @param array $data данные из полученные формы / массив $_POST
	 * @return int $userId
	 * @return false
	 */
	public function createUser($data)
	{
		// регистрация пользователя в таблице компонента Auth 
		if ($data['send_email'] === 'true') {
			// с отправкой письма 
			$userId = $this->auth->register(
				$data['email'],
				$data['password'],
				$data['username'],
				function ($selector, $token) use ($data) {
					Mailer::email_verify($data['email'], $token, $selector);
				}
			);
		} else {
			// без отправки письма
			$userId = $this->auth->register(
				$data['email'],
				$data['password'],
				$data['username']
			);
		}
		if (!is_numeric($userId)) return false;
		// обновление Роли и Статуса в таблице компонента Auth
		$this->queryBuilder->update('users', 'id', $userId, ['roles_mask' => $data['roles_mask'], 'status' => $data['status']]);

		// подготовка данных и регистрация пользователя с полученным userId в моей таблице
		$myData = $data['myInfo'];
		$myData['user_id'] = $userId;

		$this->queryBuilder->create('users_my_info', $myData);
		return $userId;
	}

	/**
	 * Верификация email
	 * @param string $selector селектор из пары селектор/токен
	 * @param string $token токен из пары селектор/токен
	 * @return string email
	 */
	public function email_verify($selector, $token)
	{
		$emailArray = $this->auth->confirmEmail($selector, $token);

		foreach ($emailArray as $email) {
			if (!is_null($email)) return $email;
		}
	}

	/**
	 * Логин пользователя
	 * Если пользователь указал "Запомнить меня", то будет создана куки сроком на 1 год.
	 * @param array $data это массив $_POST
	 * @return int $userId
	 * @return false  
	 */
	public function login($data)
	{
		// функционал "запомнить меня"
		$rememberDuration = null;
		if (isset($data['remember']) and $data['remember'] === 'true') {
			$rememberDuration = (int) (60 * 60 * 24 * 365.25);
		}
		// login
		$this->auth->login($data['email'], $data['password'], $rememberDuration);

		if (is_null($this->auth->id())) return false;

		// получение информации из моей таблицы
		$_SESSION['user'] = $this->queryBuilder->getOne('users_my_info', 'user_id', $this->auth->id(), 'ASSOC');
		unset($_SESSION['user']['id']);
		return $this->auth->id();
	}

	/**
	 * Автологин. Если пользователь указал "Запомнить меня", то он автоматически войдет при следующем посещении
	 * проверяет залогинен ли пользователь средствами компонента Auth, если да,
	 * то проверяет есть ли запись в сессии из моей таблицы. если ее нет,
	 * то получает данные из моей таблицы и вносит их в сессию. 
	 */
	public function autoLogin()
	{
		if ($this->auth->isLoggedIn()) {
			if (!isset($_SESSION['user'])) {
				$_SESSION['user'] = $this->queryBuilder->getOne('users_my_info', 'user_id', $this->auth->id(), 'ASSOC');
				unset($_SESSION['user']['id']);
			}
		}
	}

	/**
	 *  Выход из системы. полностью удаляет все содержимое сессии, кроме эксепшенов.
	 */
	public function logout()
	{
		$this->auth->logOut();
		$this->auth->destroySession();
	}

	/**
	 * Восстановление пароля: первый шаг - отправка email
	 * @param string $email email для отправки письма
	 */
	public function password_forgot($email)
	{
		$this->auth->forgotPassword($email, function ($selector, $token) use ($email) {
			Mailer::password_forgot($email, $token, $selector);
		});
	}

	/**
	 * Восстановление пароля: второй шаг - проверка токена и селектора из ссылки
	 * @param string $selector селектор из пары селектор/токен
	 * @param string $token токен из пары селектор/токен
	 */
	public function password_reset($selector, $token)
	{
		$this->auth->canResetPasswordOrThrow($selector, $token);
	}

	/**
	 * Восстановление пароля: третий шаг - проверка токена и селектора
	 * внесение нового пароля в базу
	 * @param string $selector селектор из пары селектор/токен
	 * @param string $token токен из пары селектор/токен
	 */
	public function password_change($selector, $token, $password)
	{
		$this->auth->resetPassword($selector, $token, $password);
	}

	/**
	 *  Обновление информации о пользователе в таблице 'users_my_info'
	 * @param int $userId  id пользователя
	 * @param array $data  вносимые изменения
	 */
	public function editUserInfo($userId, $data)
	{
		$this->queryBuilder->update('users_my_info', 'user_id', $userId, $data);

		// если $userId равно id текущего пользователя, т.е данные меняет владелец профиля, то обновляю данные в сессии. 
		if ($userId == $this->auth->id()) {
			$_SESSION['user'] = $this->queryBuilder->getOne('users_my_info', 'user_id', $this->auth->id(), 'ASSOC');
			unset($_SESSION['user']['id']);
		}
	}

	/**
	 * проверяет правильность ввода текущего пароля пользователя при
	 * смене email, username and password
	 * @param string $currentPassword текущий пароль пользователя
	 * @return bool
	 */
	public function currentPasswordCheck($currentPassword)
	{
		if ($this->auth->reconfirmPassword($currentPassword)) return true;
		return false;
	}

	// смена регистрационных данных пользователя
	/**
	 * User Email Changer
	 * отправляет письмо на новый email для его подтверждения
	 * @param string $newEmail новый email
	 */
	public function userEmailChanger($newEmail)
	{
		$this->auth->changeEmail($newEmail, function ($selector, $token) use ($newEmail) {
			Mailer::email_verify($newEmail, $token, $selector);
		});
	}

	/**
	 * User Password Changer
	 */
	public function userPasswordChanger($current_password, $new_password)
	{
		$this->auth->changePassword($current_password, $new_password);
	}

	/**
	 * User Username Changer
	 * @param int $userId
	 * @param string $new_username
	 */
	public function userUsernameChanger($userId, $new_username)
	{
		$data = ['username' => $new_username];
		$this->queryBuilder->update('users', 'id', $userId, $data);
	}

	/**
	 * редактирование регистрационных данных пользователя Админом
	 * @param int $userId id редактируемого пользователя
	 * @param array $data редактируемые данные 
	 */
	public function editUserRegDataByAdmin($userId, $data)
	{
		$user = $this->getUserByID($userId);
		if (!empty($data['password'])) {
			$this->auth->admin()->changePasswordForUserById($userId, $data['password']);
		}
		if ($data['username'] != $user->username) {
			$this->userUsernameChanger($userId, $data['username']);
		}
		if ($data['email'] != $user->email) {
			$this->queryBuilder->update('users', 'id', $userId, ['email' => $data['email']]);
		}
	}

	/**
	 * изменяет статус и роль пользователя
	 */
	public function statusAndRoleChanger($userId, $status, $role)
	{
		$this->queryBuilder->update('users', 'id', $userId, ['status' => $status, 'roles_mask' => $role]);
	}

	/**
	 * User Activity Changer
	 * @param int $userId
	 * @param string $new_activity
	 */
	public function userActivityChanger($userId, $new_activity)
	{
		$this->queryBuilder->update('users_my_info', 'user_id', $userId, ['activity' => $new_activity]);
	}

	// проверка ролей и права доступа пользователя
	/**
	 * Проверяет является ли пользователь админом
	 * @return true|false
	 */
	public static function isAdmin()
	{
		if (isset($_SESSION['auth_roles']) and $_SESSION['auth_roles'] === Roles::ADMIN) return true;
		return false;
	}

	/**
	 * Проверяет является ли пользователь владельцем профиля
	 * @param int $profileOwnerId  id владельца профиля
	 * @return true|false
	 */
	public static function isProfileOwner($profileOwnerId)
	{
		if (self::$authStatic->id() === $profileOwnerId) return true;
		return false;
	}

	/** 
	 *  Проверяет залогинен ли пользователь
	 * @return true|false
	 */
	public function isLoggedIn()
	{
		if ($this->auth->isLoggedIn()) {
			$this->autoLogin();
			return true;
		}
		return false;
	}

	/**
	 * Получает всех пользователей
	 * @return array возвращает двумерный массив пользователей,
	 * каждый пользователь в виде объекта
	 */
	public function getAllUsers()
	{
		$from_auth_table = $this->queryBuilder->getAll('users', 'ASSOC');
		$from_my_table = $this->queryBuilder->getAll('users_my_info', 'ASSOC');

		foreach ($from_auth_table as $user_auth) {
			foreach ($from_my_table as $user_my) {

				if ($user_my['user_id'] == $user_auth['id']) {
					unset($user_my['id'], $user_my['user_id']);

					$user_array = [
						'id' => $user_auth['id'],
						'email' => $user_auth['email'],
						'username' => $user_auth['username'],
						'roles_mask' => $user_auth['roles_mask'],
						'status' => $user_auth['status']
					];

					$user_obj = (object) array_merge($user_array, $user_my);
					$all_users_array[] = $user_obj;
					unset($user_array, $user_obj);
				}
			}
		}
		return $all_users_array;
	}

	/**
	 * Получить одного пользователя из БД
	 * @param int $userId id нужного пользователя
	 * @return object возвращает пользователя в виде объекта
	 * @return false если пользователь не найден
	 */
	public function getUserByID($userId)
	{
		$user_auth_info = $this->queryBuilder->getOne('users', 'id', $userId);
		$user_my_info = $this->queryBuilder->getOne('users_my_info', 'user_id', $userId);

		if (!$user_auth_info or !$user_my_info) {
			return false;
		}

		unset($user_my_info['id'], $user_my_info['user_id']);
		$this->user = [
			'id' => $userId,
			'email' => $user_auth_info['email'],
			'username' => $user_auth_info['username'],
			'roles_mask' => $user_auth_info['roles_mask'],
			'status' => $user_auth_info['status']
		];

		$this->user = array_merge($this->user, $user_my_info);
		$this->user = (object)$this->user;
		return $this->user;
	}


	/**
	 * Заявка на удаление профиля пользователя
	 * @param int $id Id удаляемого профиля
	 * @return false если заявка уже есть
	 * @return true при успешной отправке заявки
	 */
	public function userDeleteRequest($id)
	{
		if ($this->isExistsUserDeletingRequest($id)) {
			return false;
		}

		$this->queryBuilder->create('user_deleting_request', ['user_id' => $id, 'deleting' => 1]);
		return true;
	}

	/**
	 * Проверка на наличие в БД запроса удаления от текущего пользователя
	 * @param int $id Id проверяемого профиля
	 * @return bool
	 */
	public function isExistsUserDeletingRequest($id)
	{
		if ($this->queryBuilder->getOne('user_deleting_request', 'user_id', $id)) {
			return true;
		}
		return false;
	}

	/**
	 * Удаление из БД запроса на удаление профиля от текущего пользователя
	 * @param int $id Id проверяемого профиля	 * 
	 */
	public function UserDeletingRequestCancel($id)
	{
		if (!$this->isExistsUserDeletingRequest($id)) return;

		$this->queryBuilder->delete('user_deleting_request', 'user_id', $id);
	}

	/**
	 * Выводит всех пользователей пославших запрос на удаление
	 * @return array/false
	 */
	public function userForDeleting()
	{
		$delUsers = $this->queryBuilder->getAll('user_deleting_request', null, 'OBJ');

		if (!$delUsers) return false;

		$allUsers = $this->getAllUsers();
		foreach ($delUsers as $delUser) {
			foreach ($allUsers as $user) {

				if ($delUser->user_id == $user->id) {
					$delUser->email = $user->email;
					$delUser->avatar = $user->avatar;
					$delUser->username = $user->username;
					unset($delUser->id);
				}
			}
			$current_delUsers[] = $delUser;
		}
		return $current_delUsers;
	}

	/**
	 * удаление пользователя админом
	 * удаляет данные из всех таблиц
	 * @param int $id  id пользователя
	 */

	public function deleteUserById($id)
	{
		$this->auth->admin()->deleteUserById($id);
		$this->queryBuilder->delete('users_my_info', 'user_id', $id);
		$this->queryBuilder->delete('user_deleting_request', 'user_id', $id);
	}

	/**
	 * Подготавливает вывод пользователей для компонента пагинации
	 * @param int $userPerPage количество отображаемых на странице пользователей
	 * @param int $page номер страницы из $_GET запроса, если значение 0,
	 * то отобразятся все записи из таблицы
	 * @return array возвращает двумерный массив с пользователями в виде объектов
	 * @return false при ошибке, например если указана слишком большой номер страницы
	 * и нет данных для вывода, или указано отрицательное значение страницы
	 */
	public function usersPagination($userPerPage, $page)
	{
		$from_auth_table = $this->queryBuilder->paginator('users', $userPerPage, $page, 'ASSOC');
		if (!$from_auth_table or count($from_auth_table) == 0) return false;

		$from_my_table = $this->queryBuilder->getAll('users_my_info', null, 'ASSOC');

		foreach ($from_auth_table as $user_auth) {
			foreach ($from_my_table as $user_my) {

				if ($user_my['user_id'] == $user_auth['id']) {
					unset($user_my['id'], $user_my['user_id']);

					$user_array = [
						'id' => $user_auth['id'],
						'email' => $user_auth['email'],
						'username' => $user_auth['username'],
						'roles_mask' => $user_auth['roles_mask'],
						'status' => $user_auth['status']
					];

					$user_obj = (object) array_merge($user_array, $user_my);
					$current_users_array[] = $user_obj;
					unset($user_array, $user_obj);
				}
			}
		}
		return $current_users_array;
	}

	public function usersCount()
	{
		return $this->queryBuilder->getCount('users');
	}


	// удаляет все из таблицы `users_throttling` - защита от спама, вспомогательный метод
	public function delete_from_users_throttling()
	{
		$this->queryBuilder->deleteAll('users_throttling', true);
	}

	// Вспомогательные методы для конвертации некоторых данных
	public static function username_to_tag($username = '')
	{
		if (isset($username) and !empty($username)) {
			$username = strtolower($username);
		}
		return $username;
	}

	public static function phone_to_tag($telephone = '')
	{
		if (isset($telephone) and !empty($telephone)) {
			$telephone = str_replace(['+', '-', ' ', '(', ')'], '', $telephone);
		}
		return $telephone;
	}
}
