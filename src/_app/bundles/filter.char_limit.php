<?php

function char_limit ($string, $limit=10) {
	if(mb_strlen($string) > $limit){
		return mb_substr($string, 0, $limit, 'utf-8').'&hellip;';
	}

	return $string;
    
}