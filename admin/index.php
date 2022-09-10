<?php 
  session_start();
  $noNavbar = '';
  if (isset($_SESSION['Username'])) {

    header('Location: dashboard.php'); // Redirect to dashboard page
  }

  include "init.php";
  
// check if the user coems from http post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $username = $_POST['user'];
  $password = $_POST['pass'];
  $hashedPass = sha1($password);

//check if the user exist in the database

$stmt = $con->prepare('SELECT Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1');
$stmt->execute(array($username, $hashedPass));
$count = $stmt->rowCount();

// If count > 0 this mean the database contain record about this username

if ($count > 0) {

    $_SESSION['Username'] = $username; // Register session Name
    header('Location: dashboard.php'); // Redirect to dashboard page
    exit();

}

}

  ?>

  <form class='login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
    <h4 class='text-center'>Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete='off' />
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete='off' />
    <input class="btn btn-primary btn-block btn-lg" type="submit" value="Login">
  </form>

<?php include $tpl . 'footer.php'; ?>