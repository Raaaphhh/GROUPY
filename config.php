<?php
define('ROOT_PATH', __DIR__);
$envBase = getenv('BASE_URL');
define('BASE_URL', $envBase !== false ? $envBase : '/groupy');
require ROOT_PATH . '/Controlleur/UserController.php';