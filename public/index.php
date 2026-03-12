<?php
declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/app/Core/Router.php';
require_once BASE_PATH . '/app/Core/Database.php';
require_once BASE_PATH . '/app/Core/Controller.php';
require_once BASE_PATH . '/app/Core/CSRF.php';
require_once BASE_PATH . '/app/Core/helpers.php';

$router = new Router();

require_once BASE_PATH . '/routes/web.php';

$router->dispatch();
