<?php

/*
================================================

== Manage Members Page
== You can Add | Edit | Delete Members from Here

=================================================
*/

  session_start();

  $pageTitle = 'Members';

  if (isset($_SESSION['Username'])) {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        // Start Manage page

        if ($do == 'Manage') {

            // Manage Page

        } elseif ($do == 'Edit') { // Edit page ?>
        
            <!-- HTML code Here -->
           

<?php
        }

        include $tpl . 'footer.php';

  } else {

        header('Location: index.php');

        exit();

  }