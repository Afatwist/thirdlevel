<?php
// специальные функции и хелперы

function dd(...$args)
{
	d(...$args);
	die;
}

function redirectTo($path)
{
	header("Location: $path");
	die;
}

function redirectBack()
{
	header("Location: " . $_SERVER['HTTP_REFERER']);
	die;
}

function getComponent($className)
{
	global $container;
	return $container->get($className);
}


// алиасы для некоторых методов классов

/**
 * alias for App\Classes\Validate()->tokenGenerate()
 * @return string token
 */
function tokenGenerate()
{
	return getComponent('App\Classes\Validate')->tokenGenerate();
	//return (new App\Classes\Validate())->tokenGenerate();
}

/**
 * alias for App\Classes\UserManager()->isLoggedIn()
 * @return bool
 */
function isLoggedIn()
{
	return getComponent('App\Classes\UserManager')->isLoggedIn();
}
/**
 * alias for App\Classes\UserManager()->isAdmin()
 * @return bool
 */
function isAdmin()
{
	return getComponent('App\Classes\UserManager')->isAdmin();
}
