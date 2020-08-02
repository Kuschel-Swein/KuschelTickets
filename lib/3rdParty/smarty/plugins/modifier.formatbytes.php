<?php

function smarty_modifier_formatbytes($string)
{
    if(is_numeric($string)) {	
        return kt\system\Utils::formatBytes(intval($string));
    }
    return $string;
}
