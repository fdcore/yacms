<?php

function phone_format($string){
    preg_match('/^7([0-9]{3,3})([0-9]{3,3})([0-9]{2,2})([0-9]{2,2})/', $string, $match);

    return '+7 ('.$match[1].') '.$match[2].'-'.$match[3].'-'.$match[4];
}