<?php

$this->layout('/users/template', ['title' => 'Registration Data Change', 'page' => 'security', 'topic' => 'Регистрационные данные', 'topic_icon' => 'fa-lock']);

?>
<a class="btn btn-success" href="/users/cabinet/id=<?= $user->id ?>">
	<i class="fa fa-sun"></i>
	Кабинет</a>
<form action="/users/cabinet/id=<?= $user->id ?>/security" method="POST">
	<input name="id" value="<?= $user->id ?>" type="hidden">
	<input name="token" value="<?= tokenGenerate() ?>" type="hidden">
	<div class="row">
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Обновить эл. адрес, имя или пароль</h2>
					</div>
					<div class="panel-content">
						<!-- email -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Email</label>
							<input name="email" type="text" id="simpleinput" class="form-control" placeholder="<?= $user->email ?>" value="<?= $user->email ?>">
						</div>

						<!-- username-->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Username</label>
							<input name="username" type="text" id="simpleinput" class="form-control" placeholder="<?= $user->username ?>" value="<?= $user->username ?>">
						</div>
						При обновлении электронной почты или имени пользователя не забудьте указать свой текущий пароль
						<!-- current password -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Текущий Пароль</label>
							<input name="current_password" type="password" id="simpleinput" class="form-control">
						</div>

						<!-- new password -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Новый Пароль</label>
							<input name="new_password" type="password" id="simpleinput" class="form-control">
						</div>

						<!-- password confirmation-->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Подтверждение пароля</label>
							<input name="confirm_password" type="password" id="simpleinput" class="form-control">
						</div>

						<div class="col-md-12 mt-3 d-flex flex-row-reverse">
							<button class="btn btn-warning">Изменить</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</form>