<?php
// константы для настройки методов валидации находятся в файле app\config.php
// класс создан на основе этого компонента: 
// https://respect-validation.readthedocs.io/en/latest/ 
namespace App\Classes;

use Respect\Validation\Validator as v;


/**
 * Класс для валидации введенных данных. 
 * Проверка корректности данных и соответствия их шаблонам (email, телефон).
 * Проверка загруженного изображения по MIME типу, на размер и метод загрузки 
 * Класс не проверяет данные на уникальность!!! 
 */

class Validate
{
	private $password = '';

	/**
	 * По ключу массива $_POST ищет метод данного класса и выполняет его;
	 * При передаче параметра EASY пароль проверяется только на длину;
	 * При передаче параметра HARD пароль проверяется так же на "качество" т.е должен
	 * содержать минимум 1 заглавную букву и цифру;
	 * При передаче параметра NULL, пароль можно оставить пустым, используется
	 * при редактировании профиля пользователей админом	
	 * @param string $type EASY | HARD | NULL
	 */
	public function POST($type = 'EASY')
	{
		if (empty($_POST)) return false;

		foreach ($_POST as $key => $value) {
			if ($key == 'token') {
				$this->tokenCheck($value);
			} elseif ($key == 'password') {
				$this->password = $value;
				$this->password($value, $type);
			} elseif ($key == 'password_confirm') {
				$this->password_confirm($this->password, $value, $type);
			} elseif (method_exists($this, $key)) {
				call_user_func([$this, $key], $value);
			}
		}
	}

	/**
	 * Валидация данных в GET запросах
	 */
	public function GET()
	{
		if (empty($_GET)) return false;

		foreach ($_GET as $key => $value) {
			if (method_exists($this, $key)) {
				call_user_func([$this, $key], $value);
			}
		}
	}

	/**
	 * Валидация загружаемых файлов;
	 * @param string $type тип загружаемого файла
	 */
	public function FILES($type = 'image')
	{
		if (empty($_FILES)) return false;

		foreach ($_FILES as $key => $value) {
			if ($_FILES[$key]['error'] !== 0) return false;

			if (method_exists($this, $type)) {
				call_user_func([$this, $type], $_FILES[$key]);
			} else {
				trigger_error('$type must be "image" only!', E_USER_ERROR);
			}
		}
	}

	// токен генератор
	public function tokenGenerate()
	{
		return $_SESSION['token'] = md5(uniqid());
	}

	// токен проверка
	public function tokenCheck($token)
	{
		if (isset($_SESSION['token']) and $token == $_SESSION['token']) {
			unset($_POST['token'], $_SESSION['token']);
			return true;
		}
		throw new \App\Exceptions\Validate\IncorrectTokenException('Danger! Incorrect Token!!!');
	}

	// Методы Валидации
	// валидация id
	public function id($id)
	{
		if (!v::numericVal()->validate($id)) {
			throw new \App\Exceptions\Validate\IncorrectIdException('Incorrect Id! it must be numeric!');
		}
	}

	// Email
	public function email($email)
	{
		if (!v::email()->validate($email)) {
			throw new \App\Exceptions\Validate\IncorrectEmailException('Incorrect email! it must be just like as mailer@mail.com');
		}
	}

	// Имя пользователя
	public function username($username)
	{
		if (!v::stringType()->length(USERNAME_MIN, USERNAME_MAX)->validate($username)) {
			throw new \App\Exceptions\Validate\IncorrectUserNameException('Incorrect username! it must be min: ' . USERNAME_MIN . ' and max: ' . USERNAME_MAX);
		}
	}

	// Пароль
	/**
	 * При передаче параметра EASY пароль проверяется только на длину;
	 * При передаче параметра HARD пароль проверяется так же на "качество" т.е должен
	 * содержать минимум 1 заглавную букву и цифру;
	 * При передаче параметра NULL, пароль можно оставить пустым, используется
	 * при редактировании профиля пользователей админом.	 
	 */
	public function password($password, $type = 'EASY')
	{
		$type = strtoupper($type);
		if ($type == 'EASY' or $type == 'HARD') {
			if (!v::stringType()->length(PASSWORD_MIN, PASSWORD_MAX)->validate($password)) {
				throw new \App\Exceptions\Validate\IncorrectPasswordLengthException('Incorrect password! it must be min: ' . PASSWORD_MIN . ' and max: ' . PASSWORD_MAX);
			}
		} elseif ($type == 'HARD') {
			if (
				!v::regex('/[a-z]+/')->validate($password) and
				!v::regex('/[A-Z]+/')->validate($password) and
				!v::regex('/[0-9]+/')->validate($password)
			) {
				throw new \App\Exceptions\Validate\IncorrectPasswordContentException('Incorrect password! it must contain minimum one capital letter and minimum one digit');
			}
		} elseif ($type =='NULL') {
			return;
		} else {
			trigger_error('$type must be EASY, HARD of NULL only!', E_USER_ERROR);
		}
	}

	// Совпадение пароля
	/**
	 * При передаче параметра EASY пароль проверяется только на длину;
	 * При передаче параметра HARD пароль проверяется так же на "качество" т.е должен
	 * содержать минимум 1 заглавную букву и цифру;
	 * При передаче параметра NULL, пароль можно оставить пустым, используется
	 * при редактировании профиля пользователей админом.	 
	 */
	public function password_confirm($password, $password_confirm, $type = 'EASY')
	{
		if($type=='NULL') return;
		$this->password($password, $type);
		if ($password_confirm !== $password) {
			throw new \App\Exceptions\Validate\PasswordNotConfirmException('"Password" and "Password Confirm" must be identical');
		}
	}


	// Номер телефона
	public function phone($phone)
	{
		if (empty($phone)) return;
		if (!v::phone()->validate($phone)) {
			throw new \App\Exceptions\Validate\IncorrectPhoneException('Incorrect Phone number!');
		}
	}

	// тестовое поле ABOUT - пользователь о себе
	public function about($about)
	{
		if (empty($about)) return;
		if (!v::stringType()->length(null, ABOUT_MAX)->validate($about)) {
			throw new \App\Exceptions\Validate\IncorrectAboutLengthException('Incorrect About! it must be max: ' . ABOUT_MAX);
		}
	}





	// ВАЛИДАЦИЯ ФАЙЛОВ
	// Проверка изображения на тип, размер и был ли загружен файл через HTTP POST 
	public function image($image)
	{
		if (!v::image()->validate($image['tmp_name']) or !v::uploaded()->validate($image['tmp_name'])) {
			throw new \App\Exceptions\Validate\ItIsNotImageException('Uploaded file is not image or incorrect upload method');
		}

		if (!v::size(IMAGE_SIZE_MIN, IMAGE_SIZE_MAX)->validate($image['tmp_name'])) {
			throw new \App\Exceptions\Validate\ImageSizeException('Image must be min: ' . (int)IMAGE_SIZE_MIN . ' and max: ' . IMAGE_SIZE_MAX . ' size!');
		}
	}
}


/*   
Блок try catch со всеми исключениями данного класса
 
try {
	
} catch (\App\Exceptions\Validate\IncorrectTokenException) {
	
} catch (\App\Exceptions\Validate\IncorrectEmailException $e) {
	
} catch (\App\Exceptions\Validate\IncorrectUserNameException $e) {
	
} catch (\App\Exceptions\Validate\IncorrectPasswordLengthException $e) {
	
} catch (\App\Exceptions\Validate\IncorrectPasswordContentException $e) {
	
} catch (\App\Exceptions\Validate\PasswordNotConfirmException $e) {

} catch (\App\Exceptions\Validate\IncorrectPhoneException $e) {

} catch (\App\Exceptions\Validate\ItIsNotImageException $e) {

} catch (\App\Exceptions\Validate\ImageSizeException $e) {

} 
 */
