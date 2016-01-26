<?php

$controllers = directory_map(BASE_PATH . '_app/controllers');

if(count(($controllers) > 0)){
    foreach ($controllers as $file) {
        if(is_array($file)) continue;
        $file = mb_pathinfo($file);
        $file = $file['filename'];
        
        if($file == 'index') continue;
        
        $app->group('/'.$file, function () use($app, $file) {
            include_once BASE_PATH . '_app/controllers/'.$file.'.php';
        });
    }
}

if(file_exists(BASE_PATH . '_app/controllers/index.php'))
    include BASE_PATH . '_app/controllers/index.php';
    
