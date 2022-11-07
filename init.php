<?php 

// initlize file make edit's easier in the feture

include 'admin/config.php';

// Routes

$tpl = 'includes/templates/'; // teplates
$lang = 'includes/languages/'; // Language dir
$func = 'includes/functions/'; // Functions dir
$css = 'layout/css/'; // css dir
$js = 'layout/js/'; // js dir

// Include The Important Files

include $func . 'functions.php';
include $lang . 'en.php'; 
include $tpl . "header.php";

