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
      <h1 class='text-center'>Edit Member</h1>
      <div class="container edit-page-container">
        <form>
          <!-- Username Field -->
          <div class="form-group row align-items-center">
            <label class='col-sm-2  '>Username</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="username" class='form-control' autocomplete="off">
            </div>
          </div>
          <!-- Password Field -->
          <div class="form-group row align-items-center">
            <label class='col-sm-2 control-label'>password</label>
            <div class="col-sm-10 col-md-6">
              <input type="password" name="password" class='form-control' autocomplete="off">
            </div>
          </div>
          <!-- Email Field -->
          <div class="form-group row align-items-center">
            <label class='col-sm-2 control-label'>Email</label>
            <div class="col-sm-10 col-md-6">
              <input type="email" name="email" class='form-control'>
            </div>
          </div>
          <!-- Full Name Field -->
          <div class="form-group row align-items-center">
            <label class='col-sm-2 control-label'>Full Name</label>
            <div class="col-sm-10 col-md-6">
              <input type="text" name="full" class='form-control'>
            </div>
          </div>
          <div class="form-group row align-items-center">
            <div class="col-2"></div>
            <div class="col-sm-2">
              <input type="submit" value='Save' class='btn btn-primary btn-lg'>
            </div>
          </div>
        </form>
      </div>

<?php
    }

    include $tpl . 'footer.php';

  } else {

    header('Location: index.php');

    exit();

  }