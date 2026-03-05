<?php
define('ROOT_PATH', __DIR__);
define('BASE_URL', getenv('BASE_URL') ?: '/groupy');
require ROOT_PATH . '/Controlleur/UserController.php';
