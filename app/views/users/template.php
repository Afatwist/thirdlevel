<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= $this->e($title) ?></title>
	<meta charset="UTF-8">
	<meta name="description" content="Chartist.html">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="msapplication-tap-highlight" content="no">

	<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
	<link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">

	<link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/css/vendors.bundle.css">
	<link id="appbundle" rel="stylesheet" media="screen, print" href="/css/app.bundle.css">
	<link id="myskin" rel="stylesheet" media="screen, print" href="/css/skins/skin-master.css">
	<link rel="stylesheet" media="screen, print" href="/css/fa-solid.css">
	<link rel="stylesheet" media="screen, print" href="/css/fa-brands.css">
	<link rel="stylesheet" media="screen, print" href="/css/fa-regular.css">


	<? if ($this->e($page) == 'login') : ?>
		<link rel="stylesheet" media="screen, print" href="/css/theme-demo.css">
		<link rel="stylesheet" media="screen, print" href="/css/page-login-alt.css">
	<? elseif ($this->e($page) == 'paginator') : ?>
		<link rel="stylesheet" href="/css/paginator.css">

	<? endif ?>

</head>
<!-- class="mod-bg-1 mod-nav-link" -->

<body>
	<!-- BEGIN Page HEADER -->
	<!--  логотип и верхнее меню  -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
		<a class="navbar-brand d-flex align-items-center fw-500" href="/home">
			<img alt="logo" class="d-inline-block align-top mr-2" src="/img/logo.png"> Учебный проект</a>
		<button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
		<div class="collapse navbar-collapse" id="navbarColor02">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="/">Главная</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<? if (isLoggedIn()) : ?>
					<li class="nav-item">
						<a class="nav-link" href="/users/logout">Выйти</a>
					</li>
				<? else : ?>
					<? if ($page == 'registration') : ?>
						<span class="text-white opacity-50 ml-auto mr-2 hidden-sm-down">
							Уже зарегистрированы?
						</span>
						<a href="/users/login" class="btn-link text-white ml-auto ml-sm-0">
							Войти
						</a>
					<? else : ?>
						<li class="nav-item">
							<a class="nav-link" href="/users/login">Войти</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/users/registration">Зарегистрироваться</a>
						</li>
					<? endif ?>
				<? endif ?>
			</ul>
		</div>
	</nav>
	<!-- Конец логотип и верхнее меню -->

	<!-- Вывод заголовка страницы и flash сообщения -->
	<main id="js-page-content" role="main" class="page-content mt-3">

		<div class="subheader">
			<h1 class="subheader-title">
				<i class='subheader-icon fal <?= $this->e($topic_icon) ?>'></i>
				<?= $this->e($topic) ?>
			</h1>
		</div>
		<div>

			<?= flash()->display() ?>
		</div>

		<!-- END Page HEADER -->
		<?= $this->section('content') ?>

	</main>
	<!-- BEGIN Page FOOTER -->
	<? if ($this->e($page) !== 'login') : ?>
		<footer class="page-footer" role="contentinfo">
			<div class="d-flex align-items-center flex-1 text-muted">
				<span class="hidden-md-down fw-700">2020-<?= date('Y') ?> © Учебный проект</span>
			</div>
			<div>
				<ul class="list-table m-0">
					<li><a href="/" class="text-secondary fw-700">Home</a></li>
					<li class="pl-3"><a href="/about" class="text-secondary fw-700">About</a></li>
					<li class="pl-3"><a href="/contacts" class="text-secondary fw-700">Contacts us</a></li>
				</ul>
			</div>
		</footer>
	<? endif ?>
	<!-- END Page FOOTER -->
</body>
<script src="/js/vendors.bundle.js"></script>
<script src="/js/app.bundle.js"></script>

<? if ($this->e($page) != 'activity_status') : ?>
	<script>
		$(document).ready(function() {
			$('input[type=radio][name=contactview]').change(function() {
				if (this.value == 'grid') {
					$('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
					$('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
					$('#js-contacts .js-expand-btn').addClass('d-none');
					$('#js-contacts .card-body + .card-body').addClass('show');
				} else if (this.value == 'table') {
					$('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
					$('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
					$('#js-contacts .js-expand-btn').removeClass('d-none');
					$('#js-contacts .card-body + .card-body').removeClass('show');
				}
			});
			//initialize filter
			initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
		});
	</script>

	<script>
		$("#js-login-btn").click(function(event) {

			// Fetch form to apply custom Bootstrap validation
			var form = $("#js-login")

			if (form[0].checkValidity() === false) {
				event.preventDefault()
				event.stopPropagation()
			}

			form.addClass('was-validated');
			// Perform ajax submit here...
		});
	</script>
<? endif ?>
<? if ($this->e($page) == 'activity_status') : ?>
	<script>
		$(document).ready(function() {
			$('input[type=radio][name=contactview]').change(function() {
				if (this.value == 'grid') {
					$('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
					$('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
					$('#js-contacts .js-expand-btn').addClass('d-none');
					$('#js-contacts .card-body + .card-body').addClass('show');
				} else if (this.value == 'table') {
					$('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
					$('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
					$('#js-contacts .js-expand-btn').removeClass('d-none');
					$('#js-contacts .card-body + .card-body').removeClass('show');
				}
			});
			//initialize filter
			initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
		});
	</script>
<? endif ?>

</html>