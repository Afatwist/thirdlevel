<?php

$this->layout('users/template', ['title' => 'Registration', 'page' => 'registration', 'topic' => '', 'topic_icon' => '']);


?>
<div class="page-wrapper auth">
	<div class="page-inner bg-brand-gradient">
		<div class="page-content-wrapper bg-transparent m-0">

			<div class="flex-1" style="background: url(/img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
				<div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
					<div class="row">
						<div class="col-xl-12">
							<h2 class="fs-xxl fw-500 mt-4 text-white text-center">
								Регистрация
							</h2>
						</div>
						<div class="col-xl-6 ml-auto mr-auto">
							<div class="card p-4 rounded-plus bg-faded">


								<?= flash()->display() ?>

								<form id="js-login" action="/users/registration" method="POST">
									<input type="hidden" name="token" value="<?= tokenGenerate() ?>">

									<div class="form-group">
										<label class="form-label" for="emailverify">Email</label>
										<input name="email" type="email" id="emailverify" class="form-control" placeholder="Эл. адрес" required>
										<div class="invalid-feedback">Заполните поле.</div>
										<div class="help-block">Эл. адрес будет вашим логином при авторизации</div>
									</div>

									<div class="form-group">
										<label class="form-label" for="username">Username</label>
										<input name="username" type="text" id="username" class="form-control" required>
										<div class="invalid-feedback">Заполните поле.</div>
										<div class="help-block">Это будет вашим именем в системе</div>
									</div>

									<div class="form-group">
										<label class="form-label" for="userpassword">Пароль <br></label>
										<input name="password" type="password" id="user_password" class="form-control" required>
										<div class="invalid-feedback">Заполните поле.</div>
									</div>

									<div class="form-group">
										<label class="form-label" for="userpassword_again">Пароль еще раз <br></label>
										<input name="password_again" type="password" id="user_password_again" class="form-control" required>
										<div class="invalid-feedback">Заполните поле.</div>
									</div>


									<div class="row no-gutters">
										<div class="col-md-4 ml-auto text-right">
											<button id="js-login-btn" type="submit" class="btn btn-block btn-danger btn-lg mt-3">Регистрация</button>
										</div>
									</div>
								</form>


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>