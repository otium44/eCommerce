<?php 
  ob_start(); // output buffering start

  session_start();

  if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';

    /* Start Dashboard Page */ 

    $numUsers = 5; // the number of users will shown

    $latestUsers = getLatest("*", "users", "UserID", $numUsers); // we used it below in a loop to echo users

    $numItems = 6; // Number of latest items

    $latestItems = getLatest("*", "items", "Item_ID", $numItems); // latest items array 

    $numComments = 4; // Number of Comments

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
              <strong><a href="items.php"><?php countItems('Item_ID', 'items'); ?></a></strong>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat st-comments">
              Total Comments
              <strong>
              <a href="items.php"><?php countItems('c_id', 'comments'); ?></a>
              </strong>
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
              <div class="card-header"><i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registerd Users</div>
              <div class="card-body">
                <ul class='list-unstyled latest-users'>
                  <?php 
                    if (!empty($latestUsers)) {
                    foreach ($latestUsers as $user) {

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
                  } else {
                      echo '<div class="contener">';
                      echo '<div class="alert alert-info">There\'s No User To Show';
                      echo '</div>';
                  }
                  
                  ?>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card">
              <div class="card-header"><i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items</div>
              <div class="card-body">
              <ul class='list-unstyled latest-users'>
                  <?php 
                    if (!empty($latestItems)) {
                    foreach ($latestItems as $item) {

                      echo '<li>';
                        echo $item['Name'];
                        echo '<a class="text-light btn btn-success float-right" href="items.php?do=Edit&itemid=' . $item['item_ID'] . '">';
                          echo '<i class="fa fa-edit"></i> Edit';
                        echo '</a>';
                        if ($item['Approve'] == 0) {

                            echo "<a href='items.php?do=Approve&itemid=". $item['item_ID'] ."' class='btn btn-info float-right mr-2'><i class='fa-solid fa-check'></i> Approve</a>";
  
                          }
                      echo '</li>';
                    } 
                  } else {

                      echo '<div class="contener">';
                      echo '<div class="alert alert-info">There\'s No Item To Show';
                      echo '</div>';

                  }
                  
                  ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
              <div class="card-header"><i class="fa fa-comments-o"></i> Latest <?php echo $numComments; ?> Comments</div>
              <div class="card-body">
              <ul class='list-unstyled latest-users'>
                  <?php 
                    $stmt = $con->prepare("SELECT comments.*, users.Username AS member FROM comments INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC LIMIT $numComments");

                    $stmt->execute();
  
                    $comments = $stmt->fetchAll();

                    if (!empty($comments)) {

                    foreach($comments as $comment) {

                      echo '<div class="comment-box">';
                      echo '<span class="member-n">'. '<a href="members.php?do=Edit&userid='. $comment['user_id'] .'">' . $comment['member'] . '</a>' . '</span>';
                      echo "<a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";"";
										if ($comment['status'] == 0) {
											echo "<a href='comments.php?do=Approve&comid=". $comment['c_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
										}
                      echo '<p class="member-c">' . $comment['comment'] . '</p>';
                      echo '</div>';

                    }
                  } else {
                      echo '<div class="contener">';
                      echo '<div class="alert alert-info">There\'s No Comment To Show';
                      echo '</div>';
                  }
                  ?>
                  
                </ul>
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