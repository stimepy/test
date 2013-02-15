<?php
if (!defined('e107_INIT')) { exit; }
if ( !defined('JQUERY') ) {

define('JQUERY','<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>');

echo JQUERY;

}
if ((eregi('akismet', e_SELF)) || (eregi('e107_admin', e_SELF))){
echo "<script src='".e_PLUGIN."akismet/global.js' type='text/javascript'></script>
<link rel='stylesheet' href='".e_PLUGIN."akismet/akismet_style.css' type='text/css' media='all' /> ";
}
?>