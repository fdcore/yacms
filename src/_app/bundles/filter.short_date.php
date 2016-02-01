<?php

// принимает время в unix timestamp
// возвращает текст (сегодня в 01:23, вчера в 12:34, 3 мар 2015 в 23:45)
function short_date($a) {
    date_default_timezone_set('Europe/Moscow');

    if(is_numeric($a) == false) $a = strtotime($a);

    $ndate = date('d.m.Y', $a);
    $ndate_time = date('H:i', $a);
    $ndate_exp = explode('.', $ndate);
    $nmonth = array('','янв','фев','мар','апр','мая','июн','июл','авг','сен','окт','ноя','дек');

    foreach ($nmonth as $key => $value)
        if($key == intval($ndate_exp[1])) {
            $nmonth_name = $value;
            break;
        }

    if($ndate == date('d.m.Y')) return 'сегодня в '.$ndate_time;
    elseif($ndate == date('d.m.Y', strtotime('-1 day'))) return 'вчера в '.$ndate_time;
    else return $ndate_exp[0].' '.$nmonth_name.' '.$ndate_exp[2].' в '.$ndate_time;
}