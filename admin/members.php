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

    } elseif ($do == 'Edit') { // Edit page 

      // Check If Get Request userid is Numeric & Get the integer value of it
    
      $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

      // Select All Data depend on this ID

      $stmt = $con->prepare('SELECT* FROM users WHERE userid = ? LIMIT 1');
      
      // Execute Query

      $stmt->execute(array($userid));

      // Fetch The Data

      $row = $stmt->fetch();

      // The Row Count 

      $count = $stmt->rowCount();

      if ($stmt->rowCount() > 0) { ?>


        <h1 class='text-center'>Edit Member</h1>
        <div class="container edit-page-container">
          <form action="?do=Update" method="POST">
              <input type="hidden" name="userid" value="<?php echo $userid;  ?>">
            <!-- Username Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2  '>Username</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="username" value="<?php echo $row['Username'] ?>" class='form-control' autocomplete="off">
              </div>
            </div>
            <!-- Password Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>password</label>
              <div class="col-sm-10 col-md-6">
                <input type="password" name="oldpassword" value="<?php echo $row['Password'];  ?>">
                <input type="password" name="newpassword" class='form-control' autocomplete="off">
              </div>
            </div>
            <!-- Email Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Email</label>
              <div class="col-sm-10 col-md-6">
                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class='form-control'>
              </div>
            </div>
            <!-- Full Name Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Full Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class='form-control'>
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
      } else {

          echo 'There\'s No Such ID';

      }
    } elseif ($do == 'Update') { // Update Page

      echo "<h1 class='text-center'>Edit Member</h1>";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get Variables from form

        $id       = $_POST['userid'];
        $user     = $_POST['username'];
        $email    = $_POST['email'];
        $name     = $_POST['full'];

        // Password Trick

        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

        // Validate The Form

        $formErrors = array();

        if (strlen($user) < 4) {

          $formErrors[] =  'Username Can\'t be Less Than 4 Characters';

        }

        if (empty($user)) {

          $formErrors[] =  'Username Can\'t be empty';

        }

        if (empty($name)) {

          $formErrors[] =  'Full Name Can\'t be empty';

        }

        if (empty($email)) {

          $formErrors[] =  'Email Can\'t be empty';

        }

        foreach($formErrors as $error) {

          echo $error . '<br/>';

        }

        // Check if There's No Error Proceed The Update Operation

        if (empty($formErrors)) {

        // Update the database with this info

        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
        $stmt->execute(array($user, $email, $name, $pass, $id));

        // Echo Success Message

        echo $stmt->rowCount() . ' Record Updated';
      }

      } else {

          echo 'You Can\'t Browse This Page Directry';

      }

    }

    include $tpl . 'footer.php';

  } else {

    header('Location: index.php');

    exit();

  }