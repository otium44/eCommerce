<?php

/*
================================================

== Manage Members Page
== You can Add | Edit | Delete Members from Here

=================================================
*/

  ob_start();

  session_start();

  $pageTitle = 'Members';

  if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    // Start Manage page

    if ($do == 'Manage') { // ===== Manage Members Page =====

      $query = '';

      if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

        $query = 'AND RegStatus = 0';

      }

      $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query");
      $stmt->execute();
      $rows = $stmt->fetchAll();


      ?>

        <h1 class='text-center'>Manage Members</h1>
        <div class="container">
          <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
              <tr>
                <td>#ID</td>
                <td>Username</td>
                <td>Email</td>
                <td>Full Name</td>
                <td>Registerd Date</td>
                <td>Control</td>
              </tr>
              <?php

                foreach($rows as $row) {

                  echo "<tr>";
                    echo "<td>" . $row['UserID'] . "</td>";
                    echo "<td>" . $row['Username'] . "</td>";
                    echo "<td>" . $row['Email'] . "</td>";
                    echo "<td>" . $row['FullName'] . "</td>";
                    echo "<td>". $row['Date'] ."</td>";
                    echo "<td>
                        <a href='members.php?do=Edit&userid=". $row['UserID'] ."' class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='members.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                    
                        if ($row['RegStatus'] == 0) {

                          echo "<a href='members.php?do=Activate&userid=". $row['UserID'] ."' class='btn btn-info ml-1'><i class='fa-solid fa-check'></i> Activate</a>";

                        }

                        echo "</td>";
                  echo "</tr>";

                }

              ?>

            </table>
          </div>
          <a href='members.php?do=Add' class='btn btn-primary add-btn'><i class="fa fa-plus"></i> New Member</a>

        </div>
      <?php
    } else if ($do == 'Add'){ 
      // ===== Add Members Page =====
      ?>
      <h1 class='text-center'>Add New Member</h1>
        <div class="container edit-page-container">
          <form action="?do=Insert" method="POST">
            <!-- Username Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2  '>Username</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="username" class='form-control' autocomplete="off" required='required'  placeholder='Username To Login Into Shop' />
              </div>
            </div>
            <!-- Password Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Password</label>
              <div class="col-sm-10 col-md-6">
                <input type="password" name="password" class='form-control' autocomplete="off" required='required' placeholder='Password Must Be Hard & Complex' id='password'/>
                <i class="show-pass fa fa-eye "></i>
              </div>
            </div>
            <!-- Email Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Email</label>
              <div class="col-sm-10 col-md-6">
                <input type="email" name="email" class='form-control' required='required' placeholder='Email Must Be Valid' />
              </div>
            </div>
            <!-- Full Name Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Full Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="full" class='form-control' required='required' placeholder='Full Name Appear In Your Profile Page' />
              </div>
            </div>
            <div class="form-group row align-items-center">
              <div class="col-2"></div>
              <div class="col-sm-2">
                <input type="submit" value='Add Member' class='btn btn-primary btn-lg'>
              </div>
            </div>
          </form>
        </div>

<?php
    } elseif ($do == "Insert"){

    // ===== Insert Member Page =====

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      echo "<h1 class='text-center'>Update Member</h1>";
      echo "<div class='container'>";

      // Get Variables from form

      $user     = $_POST['username'];
      $pass     = $_POST['password'];
      $email    = $_POST['email'];
      $name     = $_POST['full'];
      
      $hashPass = sha1($_POST['password']);


      // Validate The Form

      $formErrors = array();

      if (strlen($user) < 4) {

        $formErrors[] =  'Username Can\'t be Less Than <strong>4 Characters</strong>';

      }

      if (empty($user)) {

        $formErrors[] =  'Username Can\'t be <strong>empty</strong>';

      }
      
      if (empty($pass)) {

        $formErrors[] =  'password Can\'t be <strong>empty</strong>';

      }

      if (empty($name)) {

        $formErrors[] =  'Full Name Can\'t be <strong>empty</strong>';

      }

      if (empty($email)) {

        $formErrors[] =  'Email Can\'t be <strong>empty</strong>';

      }

      foreach($formErrors as $error) {

        echo '<div class="alert alert-danger">' . $error . '</div>';

      }

      // Check if There's No Error Proceed The Update Operation

      if (empty($formErrors)) {
      
      // check if user exist in the database

      $check = checkItem("Username", "users", $user);

      if($check == 1) {

        $theMsg = '<div class="alert alert-danger>"Sorry this user is Exist\'s</div>';

        redirectHome($theMsg, 'back');

      } else {
        
      // Insert user info in the database

      $stmt = $con->prepare('INSERT INTO users (Username, Password, Email, FullName, RegStatus, Date) VALUES (?, ?, ?, ?, 1, now())');
      $stmt->execute(array($user,$hashPass,$email,$name));

      // Echo Success Message

      echo "<div class='container'>";

      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' . "</div>";

      redirectHome($theMsg);


    }
  }

  } else {

        echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">You Can\'t Browse This Page Directly</div>';

        redirectHome($theMsg);

    }
    echo "</div>";


    }
    elseif ($do == 'Edit') { // ===== Edit page =====

      // Check If Get Request userid is Numeric & Get the integer value of it
    
      $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

      // Select All Data depend on this ID

      $stmt = $con->prepare('SELECT * FROM users WHERE userid = ? LIMIT 1');
      
      // Execute Query

      $stmt->execute(array($userid));

      // Fetch The Data

      $row = $stmt->fetch();

      // The Row Count 

      $count = $stmt->rowCount();

      if ($count > 0) { ?>


        <h1 class='text-center'>Edit Member</h1>
        <div class="container edit-page-container">
          <form action="?do=Update" method="POST">
              <input type="hidden" name="userid" value="<?php echo $userid;  ?>">
            <!-- Username Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2  '>Username</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="username" value="<?php echo $row['Username'] ?>" class='form-control' autocomplete="off" required='required' />
              </div>
            </div>
            <!-- Password Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>password</label>
              <div class="col-sm-10 col-md-6">
                <input type="password" name="oldpassword" value="<?php echo $row['Password'];  ?>" style='display:none'>
                <input type="password" name="newpassword" class='form-control' autocomplete="off" placeholder='Leave Blank if you dont want to change'>
              </div>
            </div>
            <!-- Email Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Email</label>
              <div class="col-sm-10 col-md-6">
                <input type="email" name="email" value="<?php echo $row['Email'] ?>" class='form-control' required='required' />
              </div>
            </div>
            <!-- Full Name Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Full Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class='form-control' required='required' />
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

          echo "<div class='container'>";

          $theMsg = '<div class="alert alert-danger">There\'s No Such ID</div>';

          redirectHome($theMsg);

          echo "</div>";

      }
    } elseif ($do == 'Update') { // ===== Update Page =====

      echo "<h1 class='text-center'>Update Member</h1>";
      echo "<div class='container'>";

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

          $formErrors[] =  '<div class="alert alert-danger">Username Can\'t be Less Than <strong>4 Characters</strong></div>';

        }

        if (empty($user)) {

          $formErrors[] =  '<div class="alert alert-danger">Username Can\'t be <strong>empty</strong></div>';

        }

        if (empty($name)) {

          $formErrors[] =  '<div class="alert alert-danger">Full Name Can\'t be <strong>empty</strong></div>';

        }

        if (empty($email)) {

          $formErrors[] =  '<div class="alert alert-danger">Email Can\'t be <strong>empty</strong></div>';

        }

        foreach($formErrors as $error) {

          echo $error;

        }

        // Check if There's No Error Proceed The Update Operation

        if (empty($formErrors)) {

        // Update the database with this info

        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
        $stmt->execute(array($user, $email, $name, $pass, $id));

        // Echo Success Message

        echo "<div class='container'>";

        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' . "</div>";

        redirectHome($theMsg, 'back');
        
      }

      } else {

          echo "<div class='container'>";

          $theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";

          redirectHome($theMsg);

      }
      echo "</div>";

      } elseif ($do == 'Delete') { // ===== Delete Member Page =====

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

          // Check If Get Request userid is Numeric & Get the integer value of it
      
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

        // Select All Data depend on this ID

        $check = checkItem('userid', 'users', $userid);
        
        if ($check > 0) { 

            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
            $stmt->bindParam(":zuser", $userid);
            $stmt->execute();

            echo "<div class='container'>";

            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted' . "</div>";

            redirectHome($theMsg, 'back');


        } else { 

          echo "<div class='container'>";

          $theMsg = '<div class="alert alert-danger">There No Such User</div>';

          redirectHome($theMsg);

        }

      echo '</div>';

    } elseif ($do == 'Activate') { // ===== Activate Page =====

      echo "<h1 class='text-center'>Activate Member</h1>";
      echo "<div class='container'>";

        // Check If Get Request userid is Numeric & Get the integer value of it
      
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;

        // Select All Data depend on this ID

        $check = checkItem('userid', 'users', $userid);
        
        if ($check > 0) { 

            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));

            echo "<div class='container'>";

            $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Activated' . "</div>";

            redirectHome($theMsg, 'back');


        } else { 

          echo "<div class='container'>";

          $theMsg = '<div class="alert alert-danger">There No Such User</div>';

          redirectHome($theMsg);

        }

      echo '</div>';

    }

    include $tpl . 'footer.php';

  } else {

    header('Location: index.php');

    exit();

  }

  ob_end_flush(); // Release the output