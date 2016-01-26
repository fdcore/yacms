<?php
error_reporting(E_ALL|E_STRICT);

ini_set('display_errors', 'on');

define("BASE_PATH", str_replace('\\', '/',  __DIR__) . '/');

require_once BASE_PATH . 'vendor/autoload.php';

$app = require_once BASE_PATH . '_app/start.php';

$app->run();
