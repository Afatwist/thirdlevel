<?php
$this->layout('/users/template', ['title' => 'Create New User', 'page' => 'create user', 'topic' => 'Добавить пользователя', 'topic_icon' => 'fa-plus-circle']);
?>
<a class="btn btn-success" href="/admin/users/createFakerUser">
	<i class="fa fa-sun"></i>
	Сгенерировать пользователя автоматически</a> <br/>
<a class="btn btn-warning" href="/admin/users/">
	<i class="fa fa-sun"></i>
	Список всех пользователей</a>
<form action="/admin/users/create" method="POST" enctype="multipart/form-data">
	<input name="token" type="hidden" value="<?= $token ?>">
	<div class="row">

		<!-- GENERAL INFORMATION -->
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Основная Информация</h2>
					</div>
					<div class="panel-content">
						<!-- email -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Email</label>
							<input required name="email" type="email" id="simpleinput" class="form-control">
						</div>

						<div class="form-group">
							<div class="checkbox">
								<label>
									<b>
										Отправить письмо на указанный Email для верификации?
									</b>
									<p>
										<input type="radio" name="send_email" value="true">
										Отправить! <br />
										<input type="radio" name="send_email" value="false" checked> Подтвердить email автоматически!
									</p>
								</label>
							</div>
						</div>

						<!-- username -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Имя от <?= USERNAME_MIN ?> до <?= USERNAME_MAX ?> символов</label>
							<input required name="username" type="text" id="simpleinput" class="form-control">
						</div>

						<!-- password -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Пароль от <?= PASSWORD_MIN ?> до <?= PASSWORD_MAX ?> символов</label>
							<input required name="password" type="password" id="simpleinput" class="form-control">
						</div>

						<!-- password confirm -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Подтверждение Пароля</label>
							<input required name="password_confirm" type="password" id="simpleinput" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- USERS AVATAR AND description -->
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<!-- avatar -->
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Аватар и Информация о Пользователе</h2>
					</div>
					<div class="panel-content">
						<div class="form-group">
							<label class="form-label" for="example-fileinput">Загрузить аватар</label>
							<input name="avatar" type="file" id="example-fileinput" class="form-control-file">
						</div>

						<!--- description/ tell about yourself -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">
								Информация о себе (максимум <?= ABOUT_MAX ?> символов)
							</label>
							<textarea name="myInfo[about]" rows="10" id="simpleinput" class="form-control" maxlength="<?= ABOUT_MAX ?>"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- ADDITIONAL INFORMATION -->
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Дополнительная информация</h2>
					</div>
					<div class="panel-content">

						<!-- work place-->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Место работы</label>
							<input name="myInfo[work_place]" type="text" id="simpleinput" class="form-control">
						</div>

						<!-- tel -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Номер телефона</label>
							<input name="myInfo[phone]" type="tel" id="simpleinput" class="form-control" placeholder="1 123-123-1234">
						</div>

						<!-- address -->
						<div class=" form-group">
							<label class="form-label" for="simpleinput">Адрес</label>
							<input name="myInfo[address]" type="text" id="simpleinput" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- ACTIVITY, ROLE AND STATUS INFORMATION -->
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Активность, Роль и Статус пользователя</h2>
					</div>
					<div class="panel-content">

						<!-- activity -->
						<div class="form-group">
							<label class="form-label" for="example-select">Выберите Активность пользователя</label>
							<select name="myInfo[activity]" class="form-control" id="example-select">
								<?php foreach ($activities as $activity) : ?>
									<option value="<?= $activity['id']; ?>"><?= $activity['title']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- user role / roles_mask -->
						<div class="form-group">
							<label class="form-label" for="example-select">Выберите Роль пользователя</label>
							<select name="roles_mask" class="form-control" id="example-select">
								<?php foreach ($roles as $role) : ?>
									<option value="<?= $role['id']; ?>"><?= $role['title']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<!-- status -->
						<div class="form-group">
							<label class="form-label" for="example-select">Выберите Сатус пользователя</label>
							<select name="status" class="form-control" id="example-select">
								<?php foreach ($statuses as $status) : ?>
									<option value="<?= $status['id']; ?>"><?= $status['title']; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- SOCIAL NETWORK -->
		<div class="col-xl-12">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Социальные сети</h2>
					</div>
					<div class="panel-content">
						<div class="row">
							<div class="col-md-4">
								<!-- vk -->
								<div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text bg-transparent border-right-0 py-1 px-3">
											<span class="icon-stack fs-xxl">
												<i class="base-7 icon-stack-3x" style="color:#4680C2"></i>
												<i class="fab fa-vk icon-stack-1x text-white"></i>
											</span>
										</span>
									</div>
									<input name="myInfo[vk]" type="text" class="form-control border-left-0 bg-transparent pl-0">
								</div>
							</div>
							<div class="col-md-4">
								<!-- telegram -->
								<div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text bg-transparent border-right-0 py-1 px-3">
											<span class="icon-stack fs-xxl">
												<i class="base-7 icon-stack-3x" style="color:#38A1F3"></i>
												<i class="fab fa-telegram icon-stack-1x text-white"></i>
											</span>
										</span>
									</div>
									<input name="myInfo[telegram]" type="text" class="form-control border-left-0 bg-transparent pl-0">
								</div>
							</div>
							<div class="col-md-4">
								<!-- instagram -->
								<div class="input-group input-group-lg bg-white shadow-inset-2 mb-2">
									<div class="input-group-prepend">
										<span class="input-group-text bg-transparent border-right-0 py-1 px-3">
											<span class="icon-stack fs-xxl">
												<i class="base-7 icon-stack-3x" style="color:#E1306C"></i>
												<i class="fab fa-instagram icon-stack-1x text-white"></i>
											</span>
										</span>
									</div>
									<input name="myInfo[instagram]" type="text" class="form-control border-left-0 bg-transparent pl-0">
								</div>
							</div>
							<div class="col-md-12 mt-3 d-flex flex-row-reverse">
								<button class="btn btn-success">Добавить</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>