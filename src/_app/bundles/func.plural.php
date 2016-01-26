<?php
/**
 * Created by PhpStorm.
 * User: nyashkin
 * Date: 18.12.14
 * Time: 6:00
 */

use php_rutils\RUtils;

function plural($num, $arg, $type='1'){

    if($type == 1) return RUtils::numeral()->getPlural($num, $arg);
    if($type == 2) return RUtils::numeral()->choosePlural($num, $arg);


}