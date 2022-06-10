<?php
if (session_status() !== 2) session_start();

require_once "../vendor/autoload.php";

require_once "../app/config.php";
require_once "../app/functions.php";

require_once '../app/di.php';

require_once '../app/routes.php';
