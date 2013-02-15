<?php

if (!defined('e107_INIT')) { exit; }

function dollar_rep($data){
    $replace = array('$');
    $search= array('&#036;');
    return str_replace($search, $replace, $data);
}
?>