<?php

function smarty_modifier_datetime($string)
{
    if(is_numeric($string)) {	
        $monthname = date("F", $string);
        $monthname = kt\system\Utils::germanMonths[$monthname];
        $dateString = date("d", $string).". ".$monthname." ".date("Y", $string).", ".date("H:i", $string);
        $dateTime = date("Y-m-d", $string)."T".date("H:i", $string).date("P", $string);
		return '<time datetime="'.$dateTime.'" title="'.$dateString.'" class="datetime" data-timestamp="'.$string.'">'.kt\system\Utils::getRelativeTime(intval($string)).'</time>';
    }
    return $string;
}
