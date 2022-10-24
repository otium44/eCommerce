<?php
/*
================================================
== Category Page
=================================================
*/

  ob_start(); // Output buffering start

  session_start();

  $pageTitle = 'Categories';

  if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

      $sort = 'ASC';

      $sort_array = array('ASC', 'DESC');

      if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

        $sort = $_GET['sort'];

      }

      $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
      $stmt2->execute();

      $cats = $stmt2->fetchAll(); ?>

      <h1 class="text-center">Manage Categories</h1>
      <div class="container categories">
        <div class="card">
          <div class="card-header text-center">
            Manage Categories
            <div class="ordering pull-right">
              Ordering:
              <a class="<?php if($sort == 'ASC') echo 'Active'; ?>" href="?sort=ASC">Asc</a>
              <a class="<?php if($sort == 'DESC') echo 'Active'; ?>" href="?sort=DESC">Desc</a>
          </div>
          <div class="card-body">
            <?php 
            foreach($cats as $cat){
              echo "<div class='cat'>";
                echo "<div class='buttons'>";
                  echo "<a href='categories.php?do=Edit&catid=". $cat['ID'] ."' class='btn btn-primary'><i class='fa fa-edit'></i> Eidt</a>";
                  echo "<a href='categories.php?do=Delete&catid=". $cat['ID'] ."' class='btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
                echo "</div>";
                echo '<h3>' . $cat['Name'] . '</h3>';
                echo '<p>'; if($cat['Description'] == ''){ echo "This category has no description"; } else {echo $cat['Description'];} echo '</p>';
                if ($cat['Visibility'] == 1) { echo '<span class="visibility">Hidden</span>'; }
                if ($cat['Allow_Comment'] == 1) { echo '<span class="comment">Comment Disabled</span>'; }
                if ($cat['Allow_Ads'] == 1) { echo '<span class="ads">Ads Disabled</span>'; }
              echo "</div>";
              echo "<hr>";
            }
            
            ?>
          </div>
        </div>
        <a class="add-category-btn btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add New Category</a>
      </div>

      <?php 
    } elseif ($do == 'Add') { ?>

<h1 class='text-center'>Add New Category</h1>
        <div class="container edit-page-container">
          <form action="?do=Insert" method="POST">
            <!-- Name Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2  '>Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Name" class='form-control' autocomplete="off" required='required'  placeholder='Name of the Category' />
              </div>
            </div>
            <!-- Description Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Description</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="Description" class='form-control' autocomplete="off" placeholder='Describe The Category' id='password'/>
              </div>
            </div>
            <!-- Ordering Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Ordering</label>
              <div class="col-sm-10 col-md-6">
                <input type="number" name="Ordering" class='form-control' placeholder='Number to Arrange the Categories' />
              </div>
            </div>
            <!-- Visibility Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Visibility</label>
              <div class="col-sm-10 col-md-6">
                  <div>
                    <input id="vis-yes" type="radio" name="Visibility" value="0" checked />
                    <label for="vis-yes">Yes</label>
                  </div>
                  <div>
                    <input id="vis-no" type="radio" name="Visibility" value="1" />
                    <label for="vis-no">No</label>
                  </div>
              </div>
            </div>
            <!-- Commenting Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Allow Commenting</label>
              <div class="col-sm-10 col-md-6">
                  <div>
                    <input id="com-yes" type="radio" name="Commenting" value="0" checked />
                    <label for="com-yes">Yes</label>
                  </div>
                  <div>
                    <input id="com-no" type="radio" name="Commenting" value="1" />
                    <label for="com-no">No</label>
                  </div>
              </div>
            </div>
            <!-- Ads Field -->
            <div class="form-group row align-items-center">
              <label class='col-sm-2 control-label'>Allow Ads</label>
              <div class="col-sm-10 col-md-6">
                  <div>
                    <input id="ads-yes" type="radio" name="ads" value="0" checked />
                    <label for="ads-yes">Yes</label>
                  </div>
                  <div>
                    <input id="ads-no" type="radio" name="ads" value="1" />
                    <label for="ads-no">No</label>
                  </div>
              </div>
            </div>
            <div class="form-group row align-items-center">
              <div class="col-2"></div>
              <div class="col-sm-2">
                <input type="submit" value='Add Category' class='btn btn-primary btn-lg'>
              </div>
            </div>
          </form>
        </div>

      <?php
    } elseif ($do == 'Insert') {

      if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Insert Category Page

        echo "<h1 class='text-center'>Insert category</h1>";
        echo "<div class='container'>";

        // Get Variables from form

        $name             = $_POST['Name'];
        $desc             = $_POST['Description'];
        $order            = $_POST['Ordering'];
        $visible          = $_POST['Visibility'];
        $comment          = $_POST['Commenting'];
        $ads              = $_POST['ads'];

        // Check if There's No Error Proceed The Update Operation

        $check = checkItem("Name", "categories", $name);

        if ($check == 1) {

          $theMsg = '<div class="alert alert-danger">Sorry This Category Is Exist\'s</div>';

          redirectHome($theMsg, 'back', 50);

        }else { // if the category wasn't exist

          // Insert Category to database 
          
          $stmt = $con->prepare('INSERT INTO categories (Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zads)');
          $stmt->execute(array(
            'zname'    => $name,
            'zdesc'    => $desc,
            'zorder'   => $order,
            'zvisible' => $visible,
            'zcomment' => $comment,
            'zads'     => $ads
          ));

          // Echo success message 

          echo "<div class='container'>";

          $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Added' . "</div>";

          redirectHome($theMsg, 'back');

        }

      } else { // if the request wasn't POST

        echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">You Can\'t Browse This Page Directly</div>';

        redirectHome($theMsg);

      }

    } elseif ($do == 'Edit') { // edit page //
      // Check If Get Request catid is Numeric & Get the integer value of it
    
      $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;

      // Select All Data depend on this ID

      $stmt = $con->prepare('SELECT * FROM categories WHERE ID = ?');
      
      // Execute Query

      $stmt->execute(array($catid));

      // Fetch The Data

      $cat = $stmt->fetch();

      // The Cat Count 

      $count = $stmt->rowCount();

      // If there's such id show the form

      if ($count > 0) { ?>

          <h1 class='text-center'>Edit Category</h1>
                  <div class="container edit-page-container">
                    <form action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid;  ?>">
                      <!-- Name Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2  '>Name</label>
                        <div class="col-sm-10 col-md-6">
                          <input type="text" name="Name" class='form-control' required='required'  placeholder='Name of the Category' value="<?php echo $cat['Name']?>" />
                        </div>
                      </div>
                      <!-- Description Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2 control-label'>Description</label>
                        <div class="col-sm-10 col-md-6">
                          <input type="text" name="Description" class='form-control' autocomplete="off" placeholder='Describe The Category' id='password' value="<?php echo $cat['Description']?>"/>
                        </div>
                      </div>
                      <!-- Ordering Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2 control-label'>Ordering</label>
                        <div class="col-sm-10 col-md-6">
                          <input type="number" name="Ordering" class='form-control' placeholder='Number to Arrange the Categories' value="<?php echo $cat['Ordering']?>"/>
                        </div>
                      </div>
                      <!-- Visibility Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2 control-label'>Visibility</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                              <input id="vis-yes" type="radio" name="Visibility" value="0" <?php if($cat['Visibility'] == 0) echo 'Checked'; ?> />
                              <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                              <input id="vis-no" type="radio" name="Visibility" value="1" <?php if($cat['Visibility'] == 1) echo 'Checked'; ?> />
                              <label for="vis-no">No</label>
                            </div>
                        </div>
                      </div>
                      <!-- Commenting Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2 control-label'>Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                              <input id="com-yes" type="radio" name="Commenting" value="0" <?php if($cat['Allow_Comment'] == 0) echo 'Checked'; ?> />
                              <label for="com-yes">Yes</label>
                            </div>
                            <div>
                              <input id="com-no" type="radio" name="Commenting" value="1" <?php if($cat['Allow_Comment'] == 1) echo 'Checked'; ?> />
                              <label for="com-no">No</label>
                            </div>
                        </div>
                      </div>
                      <!-- Ads Field -->
                      <div class="form-group row align-items-center">
                        <label class='col-sm-2 control-label'>Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                              <input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) echo 'Checked'; ?> />
                              <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                              <input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) echo 'Checked'; ?> />
                              <label for="ads-no">No</label>
                            </div>
                        </div>
                      </div>
                      <div class="form-group row align-items-center">
                        <div class="col-2"></div>
                        <div class="col-sm-2">
                          <input type="submit" value='Add Category' class='btn btn-primary btn-lg'>
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

    } elseif ($do == 'Update') { // Update Page //

      echo "<h1 class='text-center'>Update Category</h1>";
      echo "<div class='container'>";

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get Variables from form

        $id           = $_POST['catid'];
        $name         = $_POST['Name'];
        $desc         = $_POST['Description'];
        $order        = $_POST['Ordering'];
        $visible      = $_POST['Visibility'];
        $comment      = $_POST['Commenting'];
        $ads          = $_POST['ads'];


        // Update the database with this info

        $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?  WHERE ID = ?");
        $stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

        // Echo Success Message

        echo "<div class='container'>";

        $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated' . "</div>";

        redirectHome($theMsg, 'back');
        
      

      } else {

          echo "<div class='container'>";

          $theMsg = "<div class='alert alert-danger'>You Can't Browse This Page Directly</div>";

          redirectHome($theMsg);

      }
      echo "</div>";

    } elseif ($do == 'Delete') { // Delete page //

      echo "<h1 class='text-center'>Delete Category</h1>";
      echo "<div class='container'>";

        // Check If Get Request catID is Numeric & Get the integer value of it
    
      $catid = (isset($_GET['catid']) && is_numeric($_GET['catid'])) ? intval($_GET['catid']) : 0;

      // Select All Data depend on this ID

      $check = checkItem('ID', 'categories', $catid);
      
      if ($check > 0) { 

          $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
          $stmt->bindParam(":zid", $catid);
          $stmt->execute();

          echo "<div class='container'>";

          $theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted' . "</div>";

          redirectHome($theMsg, 'back');


      } else { 

        echo "<div class='container'>";

        $theMsg = '<div class="alert alert-danger">There No Such Category</div>';

        redirectHome($theMsg);

      }

    echo '</div>';

    }

    include $tpl . 'footer.php';

  } else {

    header('Location: index.php');

    exit();

  }

  ob_end_flush(); // Release The Object

?>