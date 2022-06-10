<?php

$this->layout('users/template', ['title' => 'Password forgot', 'page' => 'password_forgot', 'topic' => 'Восстановление пароля', 'topic_icon' => '']);


?>


<form id="js-login" action="/users/password_forgot" method="POST">
	<input type="hidden" name="token" value="<?= tokenGenerate() ?>">

	<div class="form-group">
		<label class="form-label" for="emailverify">Email</label>
		<input name="email" type="email" id="emailverify" class="form-control" placeholder="Эл. адрес" required>
		<div class="invalid-feedback">Заполните поле.</div>
		<div class="help-block">На этот email будет отправлено письмо с инструкциями</div>
	</div>

	<div class="row no-gutters">
		<div class="col-md-4 ml-auto text-right">
			<button id="js-login-btn" type="submit" class="btn btn-block btn-danger btn-lg mt-3">Отправить</button>
		</div>
	</div>
</form>