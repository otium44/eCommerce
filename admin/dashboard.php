<?php 
  session_start();

  if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';

    /* Start Dashboard Page */ 

  

    ?>

    <div class="home-stats">
      <div class="container text-center">
        <h1>Dashboard</h1>
        <div class="row">
          <div class="col-md-3">
            <div class="stat">
              Total Members
              <strong><?php countItems('UserID', 'users'); ?></strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat">
              Pending Members
              <strong><a href="members.php?do=Manage&page=Pending">25</a></strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat">
              Total Items
              <strong>20</strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat">
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
              <div class="card-header"><i class="fa fa-users"></i> Latest Registerd Users</div>
              <div class="card-body">
                Test
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