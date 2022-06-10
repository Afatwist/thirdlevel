<?php

use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use Aura\SqlQuery\QueryFactory;


// Settings of Depends Injections

$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([
	Engine::class => function () {
		return new Engine('../app/views/');
	},

	PDO::class => function () {
		return new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
	},
	Auth::class => function ($container) {
		return new Auth($container->get('PDO'));
	},
	QueryFactory::class => function () {
		return new QueryFactory(DB_DRIVER);
	}
]);
$container = $containerBuilder->build();
