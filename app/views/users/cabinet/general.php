<?php
$this->layout('/users/template', ['title' => 'General Edit Info', 'page' => 'general', 'topic' => 'Общая информация', 'topic_icon' => 'fa-plus-circle']);
?>

<a class="btn btn-success" href="/users/cabinet/id=<?= $user->id ?>">
	<i class="fa fa-sun"></i>
	Кабинет</a>
<form action="/users/cabinet/id=<?= $user->id ?>/general" method="POST">
	<input type="hidden" name="id" value="<?= $user->id ?>">
	<input type="hidden" name="token" value="<?= tokenGenerate() ?>">
	<div class="row">
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Общая информация</h2>
					</div>
					<div class="panel-content">

						<!-- work place -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Место работы</label>
							<input name="work_place" type="text" id="simpleinput" class="form-control" placeholder="<?= $user->work_place ?>" value="<?= $user->work_place ?>">
						</div>

						<!-- tel -->
						<div class="form-group">
							<label class="form-label" for="simpleinput">Номер телефона</label>
							<input name="phone" type="text" id="simpleinput" class="form-control" placeholder="<?= $user->phone ?>" value="<?= $user->phone ?>">
							<div class="help-block">Телефонный номер водите в указанном формате: 1 123-123-1234</div>
						</div>

						<!-- address -->
						<div class=" form-group">
							<label class="form-label" for="simpleinput">Адрес</label>
							<input name="address" type="text" id="simpleinput" class="form-control" placeholder="<?= $user->address ?>" value="<?= $user->address ?>">
						</div>
						<div class="col-md-12 mt-3 d-flex flex-row-reverse">
							<button class="btn btn-warning">Редактировать</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>