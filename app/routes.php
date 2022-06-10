<?php

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
	// Общие страницы
	$r->get('', ['App\Controllers\HomeController', 'index']);
	$r->get('/', ['App\Controllers\HomeController', 'index']);
	$r->get('/index', ['App\Controllers\HomeController', 'index']);
	$r->get('/about', ['App\Controllers\HomeController', 'about']);
	$r->get('/contacts', ['App\Controllers\HomeController', 'contacts']);

	// ПОЛЬЗОВАТЕЛИ
	$r->addGroup('/users', function (FastRoute\RouteCollector $r) {
		// вывод пользователей
		$r->get('/userlist', ['App\controllers\UserController', 'userlist']);
		$r->get('/paginator/page={page:\d+}', ['App\controllers\UserController', 'userListPaginator']);
		$r->get('/profile/id={id:\d+}', ['App\controllers\UserController', 'profile']);

		// Регистрация
		$r->get('/registration', ['App\controllers\UserController', 'registration']);
		$r->post('/registration', ['App\controllers\UserController', 'registrationAction']);

		// подтверждение email
		$r->get('/email_verify/selector={selector:[0-9a-zA-Z_]+}/token={token:[0-9a-zA-Z_]+}', ['App\controllers\UserController', 'email_verify']);

		// login and logout
		$r->get('/login', ['App\controllers\UserController', 'login']);
		$r->post('/login', ['App\controllers\UserController', 'loginAction']);
		$r->get('/logout', ['App\controllers\UserController', 'logout']);

		//восстановление пароля
		$r->get('/password_forgot', ['App\controllers\UserController', 'password_forgot']);
		$r->post('/password_forgot', ['App\controllers\UserController', 'password_forgotAction']);
		$r->get('/password_reset/selector={selector:[0-9a-zA-Z_]+}/token={token:[0-9a-zA-Z_]+}', ['App\controllers\UserController', 'password_reset']);
		$r->post('/password_change', ['App\controllers\UserController', 'password_changeAction']);

		// Изменение личных данных / Личный кабинет
		$r->addGroup('/cabinet/id={id:\d+}', function (FastRoute\RouteCollector $r) {
			// Главная страница личного кабинета
			$r->get('', ['App\controllers\UserCabinetController', 'index']);
			$r->get('/', ['App\controllers\UserCabinetController', 'index']);
			$r->get('/index', ['App\controllers\UserCabinetController', 'index']);

			// Изменение регистрационных данных (имя, емейл, пароль)
			$r->get('/security', ['App\controllers\UserCabinetController', 'security']);
			$r->post('/security', ['App\controllers\UserCabinetController', 'securityAction']);

			// Изменение статуса активности
			$r->get('/activity', ['App\controllers\UserCabinetController', 'activity']);
			$r->post('/activity', ['App\controllers\UserCabinetController', 'activityAction']);

			// Изменение общей информации: место работы, телефон, адрес
			$r->get('/general', ['App\controllers\UserCabinetController', 'general']);
			$r->post('/general', ['App\controllers\UserCabinetController', 'generalAction']);

			// Изменение Аватарки и "о себе"
			$r->get('/media', ['App\controllers\UserCabinetController', 'media']);
			$r->post('/media', ['App\controllers\UserCabinetController', 'mediaAction']);

			// Изменение контактов в социальных сетях
			$r->get('/social', ['App\controllers\UserCabinetController', 'social']);
			$r->post('/social', ['App\controllers\UserCabinetController', 'socialAction']);

			// Удаление профиля
			$r->get('/delete', ['App\controllers\UserCabinetController', 'delete']);
			$r->post('/delete', ['App\controllers\UserCabinetController', 'deleteAction']);
			$r->post('/delete-cancel', ['App\controllers\UserCabinetController', 'deleteActionCancel']);
		});
	});

	// АДМИНКА
	$r->addGroup('/admin', function (FastRoute\RouteCollector $r) {
		$r->get('', ['App\Controllers\Admin\AdminController', 'index']);
		$r->get('/', ['App\Controllers\Admin\AdminController', 'index']);
		$r->get('/index', ['App\Controllers\Admin\AdminController', 'index']);
		// Работа с пользователями
		$r->addGroup('/users', function (FastRoute\RouteCollector $r) {
			// общая страница пользователей
			$r->get('', ['App\Controllers\Admin\UsersController', 'index']);
			$r->get('/', ['App\Controllers\Admin\UsersController', 'index']);
			$r->get('/index', ['App\Controllers\Admin\UsersController', 'index']);
			// создание пользователя вручную
			$r->get('/create', ['App\Controllers\Admin\UsersController', 'create']);
			$r->post('/create', ['App\Controllers\Admin\UsersController', 'createAction']);
			// создание пользователя автоматически - генератор пользователей
			$r->get('/createFakerUser', ['App\Controllers\Admin\UsersController', 'createFakerUserAction']);
			//	редактирование профиля пользователя
			$r->get('/edit/id={id:\d+}', ['App\Controllers\Admin\UsersController', 'edit']);
			$r->post('/edit/id={id:\d+}', ['App\Controllers\Admin\UsersController', 'editAction']);
			// удаление пользователя
			$r->get('/delete/id={id:\d+}', ['App\Controllers\Admin\UsersController', 'deleteAction']);
		});
	});
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
	$uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
	case FastRoute\Dispatcher::NOT_FOUND:
		echo "404 Not Found";
		break;
	case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		$allowedMethods = $routeInfo[1];
		echo "405 Method Not Allowed";
		break;
	case FastRoute\Dispatcher::FOUND:
		$handler = $routeInfo[1];
		$vars = $routeInfo[2];
		$container->call($handler, $vars);
		break;
}
