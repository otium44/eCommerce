<?php 

// initlize file make edit's easier in the feture

include 'config.php';

// Routes

$tpl = 'includes/templates/'; // teplates
$css = 'layout/css/'; // css dir
$js = 'layout/js/'; // js dir
$lang = 'includes/languages/'; // Language dir

// Include The Important Files

include $lang . 'en.php'; 
include $tpl . "header.php"; 
include $tpl . "navbar.php"; 