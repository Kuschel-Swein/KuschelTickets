<?php
use kt\system\Link;
/**
 *
 * {link url="?URL?"}
 *
 */
function smarty_function_link($params) {
    $url = $params['url'];
    $url = Link::get($url);
    return $url;
}
