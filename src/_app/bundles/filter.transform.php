<?php

use Intervention\Image\ImageManagerStatic as Image;

function transform($string, $height, $width, $filename='') {

    if(strstr($string, '?')) {
        $string = explode('?' , $string);
        $string = $string[0];
    }

    $src_file = $string;

    if(!strstr($string, '.')) return $string;

    $ext = explode('.', $string);
    $ext = $ext[1];

    if(strlen($ext) > 3) $ext = 'jpg';

    if($filename != '') $filename = $filename.'-';

    $filename = $filename.md5($string.$height.$width).'.'.$ext;


    if(!file_exists(BASE_PATH . '../html/static/thumbnail/'.$filename)){

        try{
            $content = @file_get_contents($string);

            if($content == '') return $string;

            $img = Image::make($content);

            if(!$img) return $string;

            $img->fit($height, $width, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save(BASE_PATH.'../html/static/thumbnail/'.$filename);

        } catch(Exception $e){
            $content = @file_get_contents($string);

            file_put_contents(BASE_PATH.'../html/static/thumbnail/'.$filename, $content);

            return '/static/thumbnail/'.$filename;
        }


    }

//    return UPLOAD_ASSETS_URL.'_thumbs/'.$filename.'?v='.time();
    return UPLOAD_ASSETS_URL.'thumbnail/'.$filename;

}
