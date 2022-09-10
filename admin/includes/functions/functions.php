<?php

/*
    Title Function that echo the page title in case the page
    has the variable $pageTitle And Echo Defualt title for other pages
*/

function getTitle() {

    global $pageTitle;

    if (isset($pageTitle)) {
        
        echo $pageTitle;

    } else {

        echo 'Default';

    }

}