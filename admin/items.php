<?php 

/*
==========================================================
=== Items Page
==========================================================
*/

ob_start(); 

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {

  include 'init.php';

  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

  if ($do == 'Manage') {

    $stmt = $con->prepare("SELECT items.*, categories.Name AS cat_name, users.Username AS item_user FROM items INNER JOIN categories ON categories.ID = items.Cat_ID INNER JOIN users ON users.UserID = items.User_ID ORDER BY item_ID DESC");

    $stmt->execute();

    $items = $stmt->fetchAll();


    if (!empty($items)) {
    ?>

        <h1 class='text-center'>Manage Items</h1>
        <div class="container">
          <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
              <tr>
                <td>#ID</td>
                <td>Name</td>
                <td>Description</td>
                <td>Price</td>
                <td>Adding Date</td>
                <td>Category</td>
                <td>Username</td>
                <td>Control</td>
              </tr>
              <?php

                foreach($items as $item) {

                  echo "<tr>";
                    echo "<td>" . $item['item_ID'] . "</td>";
                    echo "<td>" . $item['Name'] . "</td>";
                    echo "<td>" . $item['Description'] . "</td>";
                    echo "<td>" . $item['Price'] . "</td>";
                    echo "<td>" . $item['Add_Date'] ."</td>";
                    echo "<td>" . $item['cat_name'] . "</td>";
                    echo "<td>" . $item['item_user'] . "</td>";
                    echo "<td>
                        <a href='items.php?do=Edit&itemid=". $item['item_ID'] ."' class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='items.php?do=Delete&itemid=". $item['item_ID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                        if ($item['Approve'] == 0) {

                          echo "<a href='items.php?do=Approve&itemid=". $item['item_ID'] ."' class='btn btn-info ml-1'><i class='fa-solid fa-check'></i>Approve</a>";

                        }
                        echo "</td>";
                  echo "</tr>";

                } 

              ?>

            </table>
          </div>
          <a href='items.php?do=Add' class='btn btn-primary add-btn'><i class="fa fa-plus"></i> New Item</a>

        </div>
        
      <?php
          } else {

              echo '<div class="contener">';
              echo '<div class="alert alert-info">There\'s No Item To Show';
              echo '</div>'; ?>

                </div>
                  <a href='items.php?do=Add' class='btn btn-primary add-btn'><i class="fa fa-plus"></i> New Item</a>
                </div>

    <?php }

  } elseif ($do == 'Add') { ?>

      <h1 class='text-center'>Add New Item</h1>
        <div class="container edit-page-container">
          <form action="?do=Insert" method="POST">
            <!-- Name Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Name" class='form-control' autocomplete="off" required='required'  placeholder='Name of the item' />
              </div>
            </div>
            <!-- Description Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Description</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Description" class='form-control' autocomplete="off" placeholder='Description of the item' required='required' />
              </div>
            </div>
            <!-- Price Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Price</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Price" class='form-control' autocomplete="off" required='required' placeholder='Price of the item' />
              </div>
            </div>
            <!-- Country_Made Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Country of Made</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Country" class='form-control' autocomplete="off" placeholder='Country of Made' required='required' />
              </div>
            </div>
            <!-- Status Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Status</label>
              <div class="col-sm-10 col-md-6">
                <select class="form-control" name="Status">
                  <option value="0">...</option>
                  <option value="1">New</option>
                  <option value="2">Like New</option>
                  <option value="3">Used</option>
                  <option value="4">Old</option>
                </select>
              </div>
            </div>
            <!-- Members Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Member</label>
              <div class="col-sm-10 col-md-6">
                <select class="form-control" name="Member">
                  <option value="0">...</option>
                  <?php
                      $stmt = $con->prepare("SELECT * FROM users");
                      $stmt->execute();
                      $users = $stmt->fetchAll();
                      foreach($users as $user) {
                        echo "<option value='". $user['UserID'] ."'>". $user['Username'] ."</option>";
                      }
                  ?>
                </select>
              </div>
            </div>
            <!-- Category Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2'>Category</label>
              <div class="col-sm-10 col-md-6">
                <select class="form-control" name="Category">
                  <option value="0">...</option>
                  <?php
                      $stmt2 = $con->prepare("SELECT * FROM categories");
                      $stmt2->execute();
                      $cats = $stmt2->fetchAll();
                      foreach($cats as $cat) {
                        echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
                      }
                  ?>
                </select>
              </div>
            </div>
            <!-- Submit Field  -->
            <div class="form-group row align-items-center">
              <div class="col-2"></div>
              <div class="col-sm-2">
                <input type="submit" value='Add item' class='btn btn-primary btn-sm'>
              </div>
            </div>
          </form>
        </div>

      <?php

  } elseif ($do == 'Insert') {

    // ===== Insert Item Page =====

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      echo "<h1 class='text-center'>Update Item</h1>";
      echo "<div class='container'>";

      // Get Variables from form

      $name     = $_POST['Name'];
      $desc     = $_POST['Description'];
      $price    = $_POST['Price'];
      $country  = $_POST['Country'];
      $status   = $_POST['Status'];
      $member   = $_POST['Member'];
      $category = $_POST['Category'];

      // Validate The Form

      $formErrors = array();

      if (empty($name)) {

        $formErrors[] =  'Name can\'t be <strong>empty</strong>';

      }

      if (empty($desc)) {

        $formErrors[] =  'Description can\'t be <strong>empty</strong>';

      }
      
      if (empty($price)) {

        $formErrors[] =  'Price can\'t be <strong>empty</strong>';

      }

      if ($status == 0) {

        $formErrors[] =  'You Must Choose The <strong>Status</strong>';

      }

      if ($member == 0) {

        $formErrors[] =  'You Must Choose The <strong>Member</strong>';

      }

      if ($category == 0) {

        $formErrors[] =  'You Must Choose The <strong>Category</strong>';

      }

      foreach($formErrors as $error) {

        echo '<div class="alert alert-danger">' . $error . '</div>';

      }

      // Check if There's No Error Proceed The Update Operation

      if (empty($formErrors)) {
        
      // Insert user info in the database

      $stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, User_ID) VALUES (:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zmember)");

      $stmt->execute(array(
        'zname'     => $name,
        'zdesc'     => $desc,
        'zprice'    => $price,
        'zcountry'  => $country,
        'zstatus'   => $status,
        'zcat'      => $category,
        'zmember'   => $member
      ));

      // Echo Success Message

      echo "<div class='container'>";

      $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' . "</div>";

      redirectHome($theMsg, 'back');


    
  }

  } else {

        echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">You Can\'t Browse This Page Directly</div>';

        redirectHome($theMsg);

    }
    echo "</div>";

  } elseif ($do == 'Edit') {

    // Check If Get Request userid is Numeric & Get the integer value of it
    
    $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;

    // Select All Data depend on this ID

    $stmt = $con->prepare('SELECT * FROM items WHERE item_ID = ?');
    
    // Execute Query

    $stmt->execute(array($itemid));

    // Fetch The Data

    $item = $stmt->fetch();

    // The Row Count 

    $count = $stmt->rowCount();

    if ($count > 0) { ?>


      <h1 class='text-center'>Edit Item</h1>
              <div class="container edit-page-container">
                <form action="?do=Update" method="POST">
                  <input type="hidden" name="itemid" value="<?php echo $itemid;  ?>">
                  <!-- Name Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Name</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" name="Name" class='form-control' autocomplete="off" required='required'  placeholder='Name of the item' value="<?php echo $item['Name'] ?>" />
                    </div>
                  </div>
                  <!-- Description Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Description</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" name="Description" class='form-control' autocomplete="off" placeholder='Description of the item' required='required' value="<?php echo $item['Description'] ?>" />
                    </div>
                  </div>
                  <!-- Price Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Price</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" name="Price" class='form-control' autocomplete="off" required='required' placeholder='Price of the item' value="<?php echo $item['Price'] ?>" />
                    </div>
                  </div>
                  <!-- Country_Made Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Country of Made</label>
                    <div class="col-sm-10 col-md-6">
                      <input type="text" name="Country" class='form-control' autocomplete="off" placeholder='Country of Made' required='required' value="<?php echo $item['Country_Made'] ?>" />
                    </div>
                  </div>
                  <!-- Status Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Status</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-control" name="Status">
                        <option value="0">...</option>
                        <option value="1" <?php if ($item['Status'] == 1) echo 'selected';?>>New</option>
                        <option value="2" <?php if ($item['Status'] == 2) echo 'selected';?>>Like New</option>
                        <option value="3" <?php if ($item['Status'] == 3) echo 'selected';?>>Used</option>
                        <option value="4" <?php if ($item['Status'] == 4) echo 'selected';?>>Old</option>
                      </select>
                    </div>
                  </div>
                  <!-- Members Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Member</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-control" name="Member">
                        <option value="0">...</option>
                        <?php
                            $stmt = $con->prepare("SELECT * FROM users");
                            $stmt->execute();
                            $users = $stmt->fetchAll();
                            foreach($users as $user) {
                              echo "<option value='". $user['UserID'] ."'"; if ($item['User_ID'] == $user['UserID']) echo 'selected';echo ">". $user['Username'] ."</option>";
                            }
                        ?>
                      </select>
                    </div>
                  </div>
                  <!-- Category Field -->
                  <div class="form-group row align-items-center">
                    <label class='col-sm-2'>Category</label>
                    <div class="col-sm-10 col-md-6">
                      <select class="form-control" name="Category">
                        <option value="0">...</option>
                        <?php
                            $stmt2 = $con->prepare("SELECT * FROM categories");
                            $stmt2->execute();
                            $cats = $stmt2->fetchAll();
                            foreach($cats as $cat) {
                              echo "<option value='". $cat['ID'] ."'"; if ($item['Cat_ID'] == $cat['ID']) echo 'selected';echo">". $cat['Name'] ."</option>";
                            }
                        ?>
                      </select>
                    </div>
                  </div>
                  <!-- Submit Field  -->
                  <div class="form-group row align-items-center">
                    <div class="col-2"></div>
                    <div class="col-sm-2">
                      <input type="submit" value='Save item' class='btn btn-primary btn-sm'>
                    </div>
                  </div>
                </form>
                <?php
                $stmt = $con->prepare("SELECT comments.*, users.Username AS member FROM comments INNER JOIN users ON users.UserID = comments.user_id WHERE item_id = ?");

                $stmt->execute(array($itemid));

                $rows = $stmt->fetchAll();

                if (!empty($rows)) {
      ?>

        <h1 class='text-center'>Manage [<?php echo $item['Name'] ?>] Comments</h1>
        <div class="container">
          <div class="table-responsive">
            <table class="main-table text-center table table-bordered">
              <tr>
                <td>Comment</td>
                <td>Username</td>
                <td>Added Date</td>
                <td>Control</td>
              </tr>
              <?php

                foreach($rows as $row) {

                    echo "<tr>";
                    echo "<td>" . $row['comment'] . "</td>";
                    echo "<td>" . $row['member'] . "</td>";
                    echo "<td>" . $row['comment_date'] ."</td>";
                    echo "<td>
                        <a href='comments.php?do=Edit&comid=". $row['c_id'] ."' class='btn btn-outline-success'><i class='fa fa-edit'></i> Edit</a>
                        <a href='comments.php?do=Delete&comid=". $row['c_id'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
                    
                        if ($row['status'] == 0) {

                          echo "<a href='comments.php?do=Approve&comid=". $row['c_id'] ."' class='btn btn-info ml-1'><i class='fa-solid fa-check'></i> Approve</a>";

                        }

                        echo "</td>";
                  echo "</tr>";

                }

              ?>

            </table>
          </div>
                <?php } ?>
        </div>
              </div>

<?php
    } else {

        echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">There\'s No Such ID</div>';

        redirectHome($theMsg);

        echo "</div>";

    }

  } elseif ($do == 'Update') {

      echo "<h1 class='text-center'>Update Item</h1>";
      echo "<div class='container'>";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get Variables from form

        $id        = $_POST['itemid'];
        $name      = $_POST['Name'];
        $desc      = $_POST['Description'];
        $price     = $_POST['Price'];
        $country   = $_POST['Country'];
        $status    = $_POST['Status'];
        $member    = $_POST['Member'];
        $cat       = $_POST['Category'];

        // Validate The Form

      $formErrors = array();

      if (empty($name)) {

        $formErrors[] =  'Name can\'t be <strong>empty</strong>';

      }

      if (empty($id)) {

        $formErrors[] =  'ID can\'t be <strong>empty</strong>';

      }

      if (empty($desc)) {

        $formErrors[] =  'Description can\'t be <strong>empty</strong>';

      }
      
      if (empty($price)) {

        $formErrors[] =  'Price can\'t be <strong>empty</strong>';

      }

      if ($status == 0) {

        $formErrors[] =  'You Must Choose The <strong>Status</strong>';

      }

      if ($member == 0) {

        $formErrors[] =  'You Must Choose The <strong>Member</strong>';

      }

      if ($cat == 0) {

        $formErrors[] =  'You Must Choose The <strong>Category</strong>';

      }

      foreach($formErrors as $error) {

        echo '<div class="alert alert-danger">' . $error . '</div>';

      }

      // Check if There's No Error Proceed The Update Operation

      if (empty($formErrors)) {

        // Update the database with this info

        $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Cat_ID = ?, User_ID = ? WHERE Item_ID = ?");
        $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $id));

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

  } elseif ($do == 'Delete') {

        echo "<h1 class='text-center'>Delete Item</h1>";
        echo "<div class='container'>";

          // Check If Get Request userid is Numeric & Get the integer value of it
      
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;

        // Select All Data depend on this ID

        $check = checkItem('item_ID', 'items', $itemid);
        
        if ($check > 0) { 

            $stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
            $stmt->bindParam(":zid", $itemid);
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

  } elseif ($do == 'Approve') {

      echo "<h1 class='text-center'>Approve Item</h1>";
      echo "<div class='container'>";

        // Check If Get Request userid is Numeric & Get the integer value of it
      
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;

        // Select All Data depend on this ID

        $check = checkItem('item_ID', 'items', $itemid);
        
        if ($check > 0) { 

            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
            $stmt->execute(array($itemid));

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

ob_end_flush(); 

?>