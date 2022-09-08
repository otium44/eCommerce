<?php 
  include "init.php";
  include $tpl . 'header.php'; ?>

  <form class='login' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST'>
    <h4 class='text-center'>Admin Login</h4>
    <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete='off' />
    <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete='off' />
    <input class="btn btn-primary btn-block btn-lg" type="submit" value="Login">
  </form>

<?php include $tpl . 'footer.php'; ?>
