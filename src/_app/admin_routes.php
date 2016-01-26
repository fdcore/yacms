<?php

$controllers = directory_map('_app/controllers/admin');

foreach ($controllers as $file) {
    $file = mb_pathinfo($file);
    $file = $file['filename'];

    if($file == 'index') continue;

    $app->group('/admin/'.$file, function () use($app, $file) {
        include_once '_app/controllers/admin/'.$file.'.php';
    });
}


if(file_exists(BASE_PATH.'/_app/controllers/admin/index.php')){
    $app->group('/admin', function () use($app) {
        include_once '_app/controllers/admin/index.php';
    });
}
