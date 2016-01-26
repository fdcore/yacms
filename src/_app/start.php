<?php

function __autoload($class_name) {
    echo $class_name;
    if(file_exists(BASE_PATH . '_app/libraries/' . $class_name . '.php')){
        include_once BASE_PATH . '_app/libraries/' . $class_name . '.php';
    }
}

require_once BASE_PATH . '_app/function.php';
require_once BASE_PATH . '_app/model.php';
require_once BASE_PATH . '_app/libraries/Yacms.php';
require_once BASE_PATH . '_app/libraries/Tiny.php';

$app = new \Slim\Slim();

$app->config = Yacms::loadConfigs();

foreach($app->config as $key=>$value){
    $app->config($key, $value);
}

$template_engine = $app->config['template_engine'];

if(file_exists(BASE_PATH . '/_app/libraries/template_engine/' . $template_engine . '.php')){
    require_once BASE_PATH . '/_app/libraries/template_engine/' . $template_engine . '.php';
}

$app->view(new $template_engine());
$app->add(new \Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware);
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => $app->config['cookies.lifetime'],
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'session',
    'secret' => $app->config['session_secret'],
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

$app->model = new DB();

require_once __DIR__ . '/admin_routes.php';
require_once __DIR__ . '/routes.php';

return $app;
