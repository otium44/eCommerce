<?php 

// initlize file make edit's easier in the feture

include 'config.php';

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

// Include navbar in all pages expect the one with $noNavbar Vairbale

if (!isset($noNavbar)){include $tpl . "navbar.php";}