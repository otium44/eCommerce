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

// Redirect Function v2.0
// [This Function Accept Parameters]
// $theMsg = Echo The Message [error , success]
// $url = the link you want to redirect to 
// $seconds = Seconds Before Redirecting

function redirectHome($theMsg, $url = null, $seconds = 3) {

    if ($url === null) {

        $url = 'index.php';

        $link = 'HomePage';

    } else { 

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

            $url = $_SERVER['HTTP_REFERER'];

            $link = 'Previous Page';

        } else {

            $url = 'index.php';

            $link = 'HomePage';

        }

       
    }

    echo $theMsg;

    echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds.</div>";

    header("refresh:$seconds;url=$url");

    exit();
}  

// check item function v1.0
// Function to check item in database 
// $select = the item to select ex: [table, user, item, category]
// $from = the table to select from ex: [table, user, item, category]
// $value = the value of select ex: [table, user, item, category]

function checkItem($select, $from, $value) {

    global $con;

    $stmt2 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

    $stmt2->execute(array($value));

    $count = $stmt2->rowCount();

    return $count;

}