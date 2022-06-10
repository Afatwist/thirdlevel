<html>

<head>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title><?= $this->e($title) ?></title>
</head>

<body>
	<nav>
		<ul>
			<li><a href="/">Home Page</a></li>
			<li><a href="/about">About</a></li>
			<li><a href="/contacts">Contacts</a></li>
		</ul>
		<ul>
			<li><a href="/users/registration">Регистрация</a></li>
			<li><a href="/users/login">Войти</a></li>
			<li><a href="/users/userlist">Список Всех пользователей</a></li>
			<li><a href="/users/paginator/page=1">Постраничный вывод пользователей</a></li>
			<li><a href="/admin">Админка</a></li>
		</ul>
	</nav>
	<?= flash()->display() ?>
	<?= $this->section('content') ?>
</body>

</html>