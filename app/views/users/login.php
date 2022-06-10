<?php

$this->layout('users/template_small', ['title' => 'Login', 'page' => 'login', 'topic' => '', 'topic_icon' => '']);

?>

<form action="/users/login" method="POST">
	<input type="hidden" name="token" value="<?= tokenGenerate()?>">
	
	<div class="form-group">
		<label class="form-label" for="username">Email</label>
		<input required name="email" type="email" id="username" class="form-control" value="">
	</div>
	<div class="form-group">
		<label class="form-label" for="password">Пароль</label>
		<input required name="password" type="password" id="password" class="form-control">
	</div>
	<div class="form-group text-left">
		<div class="custom-control custom-checkbox">
			<input name="remember" value="true" type="checkbox" class="custom-control-input" id="remember">
			<label class="custom-control-label" for="remember">Запомнить меня</label>
		</div>
	</div>
	<button type="submit" class="btn btn-default float-right">Войти</button>
</form>
<div class="blankpage-footer text-center">
	Нет аккаунта? <a href="/users/registration"><strong>Зарегистрироваться</strong>
</div>
<div class="blankpage-footer text-center">
	Забыли пароль? <a href="/users/password_forgot"><strong>Восстановить пароль</strong>
</div>