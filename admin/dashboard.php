<?php 
  ob_start(); // output buffering start

  session_start();

  if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';

    /* Start Dashboard Page */ 

    $LatestUsers = 5; // the number of users will shown
    $theLatest = getLatest("*", "users", "UserID", $LatestUsers); // we used it below in a loop to echo users

    ?>

    <div class="home-stats">
      <div class="container text-center">
        <h1>Dashboard</h1>
        <div class="row">
          <div class="col-md-3">
            <div class="stat st-members">
              Total Members
              <strong><a href="members.php"><?php countItems('UserID', 'users'); ?></a></strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-pending">
              Pending Members
              <strong><a href="members.php?do=Manage&page=Pending">
                <?php echo checkItem("RegStatus", "users", 0); ?>
              </a></strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-items">
              Total Items
              <strong>20</strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-comments">
              Total Comments
              <strong>2030</strong>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="latest">
      <div class="container">
        <div class="row">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header"><i class="fa fa-users"></i> Latest <?php echo $LatestUsers; ?> Registerd Users</div>
              <div class="card-body">
                <ul class='list-unstyled latest-users'>
                  <?php 

                    foreach ($theLatest as $user) {

                      echo '<li>';
                        echo $user['Username'];
                        echo '<a class="text-light btn btn-success float-right" href="members.php?do=Edit&userid=' . $user['UserID'] . '">';
                          echo '<i class="fa fa-edit"></i> Edit';
                        echo '</a>';
                        if ($user['RegStatus'] == 0) {

                            echo "<a href='members.php?do=Activate&userid=". $user['UserID'] ."' class='btn btn-info float-right mr-2'><i class='fa-solid fa-check'></i> Activate</a>";
  
                          }
                      echo '</li>';
                    }
                  
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header"><i class="fa fa-tag"></i> Latest Items</div>
              <div class="card-body">
                Test
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
    /* End Dashboard Page */

    include $tpl . 'footer.php';

  } else {

    header('Location: index.php');
    exit();

  }
  ob_end_flush();