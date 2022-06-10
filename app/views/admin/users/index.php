<?php

use App\Classes\ImageManager;
use App\Classes\Roles;
use App\Classes\Status;

$this->layout('/users/template', ['title' => 'User list for Admin', 'page' => 'index', 'topic' => 'Списки всех пользователей', 'topic_icon' => 'fa-plus-circle']);
?>

<h2>Список всех пользователей</h2>
<a class="btn btn-success" href="/admin/users/create">
	<i class="fa fa-sun"></i>
	Создать пользователя вручную</a><br/>
<a class="btn btn-warning" href="/admin/users/createFakerUser">
	<i class="fa fa-sun"></i>
	Сгенерировать пользователя автоматически</a>
<table class="table m-0">
	<thead>
		<tr>
			<th>id</th>
			<th>Аватар</th>
			<th>Логин</th>
			<th>E-mail</th>
			<th>Роль</th>
			<th>Статус</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($users as $user) : ?>
			<tr>
				<th scope="row"><?= $user->id ?></th>
				<td>
					<img src="<?= ImageManager::getAvatar($user->avatar) ?>" width="75">
				</td>
				<td><?= $user->username ?></td>
				<td><?= $user->email ?></td>
				<td><?= Roles::getRole($user->roles_mask) ?></td>
				<td><?= Status::getStatus($user->status) ?></td>
				<td>
					<!-- Меню действий -->
					<a href="/users/profile/id=<?= $user->id ?>" class="btn btn-info">Посмотреть</a>
					<a href="/admin/users/edit/id=<?= $user->id ?>" class="btn btn-warning">Изменить</a>
					<a href="/admin/users/delete/id=<?= $user->id ?>" class="btn btn-danger" onclick="return confirm('are you sure?');">Удалить</a>


					<!-- Конец меню действий -->
				</td>
			</tr>
		<? endforeach ?>

	</tbody>
</table>

<h2>Список пользователей ожидающих подтверждения регистрации</h2>
<table class="table m-0">
	<thead>
		<tr>
			<th>id</th>
			<th>Аватар</th>
			<th>Логин</th>
			<th>E-mail</th>
			<th>Роль</th>
			<th>Статус</th>
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($users as $user) : ?>
			<? if ($user->status != Status::PENDING_REVIEW) continue ?>
			<tr>
				<th scope="row"><?= $user->id ?></th>
				<td>
					<img src="<?= ImageManager::getAvatar($user->avatar) ?>" width="75">
				</td>
				<td><?= $user->username ?></td>
				<td><?= $user->email ?></td>
				<td><?= Roles::getRole($user->roles_mask) ?></td>
				<td><?= Status::getStatus($user->status) ?></td>
				<td>
					<!-- Меню действий -->
					<a href="/users/profile/id=<?= $user->id ?>" class="btn btn-info">Посмотреть</a>
					<a href="/admin/users/edit/id=<?= $user->id ?>" class="btn btn-warning">Изменить</a>
					<a href="/admin/users/delete/id=<?= $user->id ?>" class="btn btn-danger" onclick="return confirm('are you sure?');">Удалить</a>



					<!-- Конец меню действий -->
				</td>
			</tr>
		<? endforeach ?>

	</tbody>
</table>

<h2>Запросы на удаление</h2>
<? if (!$usersForDeleting) : ?>
	<h3>Запросы отсутствуют!</h3>
<? else : ?>
	<table class="table m-0">
		<thead>
			<tr>
				<th>id</th>
				<th>Аватар</th>
				<th>Логин</th>
				<th>E-mail</th>
				<th>Дата запроса</th>
				<th>Действия</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($usersForDeleting as $user) : ?>
				<tr>
					<th scope="row"><?= $user->user_id ?></th>
					<td>
						<img src="<?= ImageManager::getAvatar($user->avatar) ?>" width="75">
					</td>
					<td><?= $user->username ?></td>
					<td><?= $user->email ?></td>
					<th scope="row"><?= $user->date ?></th>
					<td>
						<!-- Меню действий -->
						<a href="/admin/users/delete/id=<?= $user->user_id ?>" class="btn btn-danger" onclick="return confirm('are you sure?');">Удалить</a>
						<a href="/users/profile/id=<?= $user->user_id ?>" class="btn btn-warning">Посмотреть</a>


						<!-- Конец меню действий -->
					</td>
				</tr>
			<? endforeach ?>

		</tbody>
	</table>
<? endif ?>