<?php

use App\Classes\ImageManager;

$this->layout('/users/template', ['title' => 'Edit Media Info', 'page' => 'edit media info', 'topic' => 'Загрузить аватар и рассказать о себе', 'topic_icon' => 'fa-image']);

?>
<a class="btn btn-success" href="/users/cabinet/id=<?= $user->id ?>">
	<i class="fa fa-sun"></i>
	Кабинет</a>

<form action="/users/cabinet/id=<?= $user->id ?>/media" enctype="multipart/form-data" method="POST">
	<input name="id" value="<?= $user->id ?>" type="hidden">
	<input name="token" value="<?= tokenGenerate() ?>" type="hidden">
	<div class="row">
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Текущий аватар</h2>
					</div>
					<div class="panel-content">
						<div class="form-group">

							<img src="<?= ImageManager::getAvatar($user->avatar) ?>" alt="" class="img-responsive" width="200">
						</div>

						<div class="form-group">
							<label class="form-label" for="example-fileinput">Выберите аватар</label>
							<input name='avatar' type="file" id="example-fileinput" class="form-control-file">
						</div>

						<div class="form-group">
							<label class="form-label" for="simpleinput">
								Информация о себе (максимум <?= ABOUT_MAX ?> символов)
							</label>
							<textarea name="about" rows="10" id="simpleinput" class="form-control" maxlength="<?= ABOUT_MAX ?>"><?= $user->about ?></textarea>
						</div>

						<div class="col-md-12 mt-3 d-flex flex-row-reverse">
							<button class="btn btn-warning">Отправить</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>