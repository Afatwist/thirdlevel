<?php

use App\Classes\ImageManager;
use App\Classes\Activity;
use App\Classes\Roles;
use App\Classes\Status;

$this->layout('users/template', ['title' => 'Profile of ' . $user->username, 'page' => 'profile', 'topic' => "Личный кабинет: $user->username", 'topic_icon' => 'fa-user']);

?>
<div>
	<h3>Действия с профилем:</h3>

	<a class="btn btn-warning" href="/users/cabinet/id=<?= $user->id ?>/security">
		<i class="fa fa-lock"></i>
		Изменить регистрационные данные</a>
	<a class="btn btn-primary" href="/users/cabinet/id=<?= $user->id ?>/general">
		<i class="fa fa-edit"></i>
		Редактировать общую информацию</a>
	<a class="btn btn-info" href="/users/cabinet/id=<?= $user->id ?>/activity">
		<i class="fa fa-edit"></i>
		Изменить Активность</a>
	<a class="btn btn-secondary" href="/users/cabinet/id=<?= $user->id ?>/media">
		<i class="fa fa-camera"></i>
		Изменить автар и рассказать о себе</a>
	<a class="btn btn-success" href="/users/cabinet/id=<?= $user->id ?>/social">
		<i class="fa fa-sun"></i>
		Изменить контакты в соцсетях</a>
	<a class="btn btn-danger" href="/users/cabinet/id=<?= $user->id ?>/delete" onclick="return confirm('are you sure?');">
		<i class="fa fa-window-close"></i>
		Удалить профиль</a>
</div>
<br /><br />

<div class="row">
	<div class="col-lg-6 col-xl-6 m-auto">
		<!-- profile summary -->
		<div class="card mb-g rounded-top">
			<div class="row no-gutters row-grid">

				<div class="col-12">
					<div class="d-flex flex-column align-items-center justify-content-center p-4">

						<img src="<?= ImageManager::getAvatar($user->avatar) ?>" class="rounded-circle shadow-2 img-thumbnail" alt="">
						<h5 class="mb-0 fw-700 text-center mt-3">
							Id : <?= $user->id ?> <br />
							User Name : <?= $user->username ?> <br />
							Work Place : <?= $user->work_place ?>
						</h5>

						<div class="mt-4 text-center demo">
							<a href="javascript:void(0);" class="fs-xl" style="color:#C13584">
								<i class="fab fa-instagram"><?= $user->instagram ?></i>
							</a>
							<a href="javascript:void(0);" class="fs-xl" style="color:#4680C2">
								<i class="fab fa-vk"><?= $user->vk ?></i>
							</a>
							<a href="javascript:void(0);" class="fs-xl" style="color:#0088cc">
								<i class="fab fa-telegram"><?= $user->telegram ?></i>
							</a>
						</div>

					</div>
				</div>

				<div class="col-12">
					<div class="col-1"></div>
					<div class="col-9">
						<div> Активность пользователя: <?= Activity::getActivity($user->activity) ?>
						</div>
						<div> Статус пользователя: <?= Status::getStatus($user->status) ?></div>
						<div> Роль пользователя: <?= Roles::getRole($user->roles_mask) ?></div>
					</div>
				</div>
				<div class="col-12">

					<div class="col-1"></div>
					<div class="col-9">
						О себе:
						<?= $user->about ?>
					</div>
				</div>

				<div class="col-12">
					<div class="p-3 text-center">
						<a href="tel:+<?= $user->phone  ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
							<i class="fas fa-mobile-alt text-muted mr-2"></i> +<?= $user->phone ?></a>
						<a href="mailto:<?= $user->email ?>" class="mt-1 d-block fs-sm fw-400 text-dark">
							<i class="fas fa-mouse-pointer text-muted mr-2"><?= $user->email ?></i> </a>
						<address class="fs-sm fw-400 mt-4 text-muted">
							<i class="fas fa-map-pin mr-2"> <?= $user->address ?> </i>
						</address>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>