<?php

$controllers = directory_map(BASE_PATH . '_app/controllers/admin');

if(count($controllers) > 0) {
	foreach ($controllers as $file) {
	    $file = mb_pathinfo($file);
	    $file = $file['filename'];

	    if($file == 'index') continue;

	    $app->group('/admin/'.$file, function () use($app, $file) {
	        include_once BASE_PATH . '_app/controllers/admin/'.$file.'.php';
	    });
	}
}


if(file_exists(BASE_PATH . '_app/controllers/admin/index.php')){

    $app->group('/admin', function () use($app) {
        include_once BASE_PATH . '_app/controllers/admin/index.php';
    });

}
