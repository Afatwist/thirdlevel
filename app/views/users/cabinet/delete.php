<?php

$this->layout('/users/template', ['title' => 'Deleting Profile', 'page' => 'delete', 'topic' => 'Удаление профиля', 'topic_icon' => 'fa-lock']);

?>
<? if ($requestExists) : ?>
	<form action="/users/cabinet/id=<?= $user->id ?>/delete-cancel" method="POST">
		<input name="id" value="<?= $user->id ?>" type="hidden">
		<input name="token" value="<?= tokenGenerate() ?>" type="hidden">

		<div class="row">
			<div class="col-xl-6">
				<div id="panel-1" class="panel">
					<div class="panel-container">
						<div class="panel-hdr">
							<h2>
								Вы уже отправили запрос на удаление! <br />
								Отменить?
							</h2>
						</div>
						<div class="panel-content">
							<div class="col-md-12 mt-3 d-flex flex-row-reverse">
								<button class="btn btn-warning">Отменить запрос!</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</form>

<? else : ?>
	<form action="/users/cabinet/id=<?= $user->id ?>/delete" method="POST">
		<input name="id" value="<?= $user->id ?>" type="hidden">
		<input name="token" value="<?= tokenGenerate() ?>" type="hidden">
		<div class="row">
			<div class="col-xl-6">
				<div id="panel-1" class="panel">
					<div class="panel-container">
						<div class="panel-hdr">
							<h2>Для подтверждения удаления профиля введите пароль</h2>
						</div>
						<div class="panel-content">

							<!-- current password -->
							<div class="form-group">
								<label class="form-label" for="simpleinput">Текущий Пароль</label>
								<input name="current_password" type="password" id="simpleinput" class="form-control">
							</div>

							<div class="form-group">
								<div class="checkbox">
									<label>
										<b>
											Вы точно уверены?
										</b>
										<p>
											<input type="checkbox" name="sure" value="true">
											ДА! <br />
										</p>
									</label>
								</div>
							</div>

							<div class="col-md-12 mt-3 d-flex flex-row-reverse">
								<button class="btn btn-warning">Удалить</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</form>
<? endif ?>