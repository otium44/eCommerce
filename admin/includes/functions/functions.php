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

// Redirect Function [This Function Accept Parameters]
// $errorMsg = Echo The Error Message
// $seconds = Seconds Before Redirecting

function redirectHome($errorMsg, $seconds = 3) {

    echo "<div class='alert alert-danger'>$errorMsg</div>";

    echo "<div class='alert alert-info'>You Will Be Redirected To HomePage After $seconds Seconds.</div>";

    header("refresh:$seconds;url=index.php");

    exit();
}