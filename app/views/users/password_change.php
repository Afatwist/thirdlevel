<?php

$this->layout('users/template', ['title' => 'Password change', 'page' => 'password_change', 'topic' => 'Введите новый пароль', 'topic_icon' => '']);


?>

<form id="js-login" action="/users/password_change" method="POST">
	<input type="hidden" name="token" value="<?= tokenGenerate() ?>">
	<input type="hidden" name="selector_check" value="<?= $selector_check ?>">
	<input type="hidden" name="token_check" value="<?= $token_check ?>">

	<div class="form-group">
		<label class="form-label" for="emailverify">Введите новый пароль</label>
		<input name="password" type="password" id="emailverify" class="form-control" placeholder="Пароль" required>
		<div class="invalid-feedback">Заполните поле.</div>
	</div>
	<div class="form-group">
		<label class="form-label" for="emailverify">Введите новый пароль еще раз</label>
		<input name="password_confirm" type="password" id="emailverify" class="form-control" placeholder="Пароль" required>
		<div class="invalid-feedback">Заполните поле.</div>
	</div>

	<div class="row no-gutters">
		<div class="col-md-4 ml-auto text-right">
			<button id="js-login-btn" type="submit" class="btn btn-block btn-danger btn-lg mt-3">Отправить</button>
		</div>
	</div>
</form>