<?php

use App\Classes\Activity;

$this->layout('/users/template', ['title' => 'Activity', 'page' => 'activity', 'topic' => 'Установить статус активности', 'topic_icon' => 'fa-sun']);


?>
<a class="btn btn-success" href="/users/cabinet/id=<?= $user->id ?>">
	<i class="fa fa-sun"></i>
	Кабинет</a>
<!--  Начало формы -->
<form action="/users/cabinet/id=<?= $user->id ?>/activity" method="POST">
	<input type="hidden" name="token" value="<?= tokenGenerate() ?>">
	<input type="hidden" name="id" value="<?= $user->id; ?>">
	<div class="row">
		<div class="col-xl-6">
			<div id="panel-1" class="panel">
				<div class="panel-container">
					<div class="panel-hdr">
						<h2>Текущий статус активности <?= Activity::getActivity($user->activity) ?> </h2>
					</div>
					<div class="panel-content">
						<div class="row">
							<div class="col-md-4">
								<!-- activity status -->
								<div class="form-group">
									<label class="form-label" for="example-select">Выберите статус</label>
									<select name="new_activity" class="form-control" id="example-select">
										<?php foreach (Activity::getActivityList() as $activity) : ?>
											<option <? echo $user->activity == $activity['id'] ? 'selected' : '' ?> value="<?= $activity['id']; ?>"><?= $activity['title']; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-12 mt-3 d-flex flex-row-reverse">
								<button class="btn btn-warning">Set Status</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- Конец формы -->