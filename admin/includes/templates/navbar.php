<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-dark bg-dark">
  <div class="container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class='navbar-brand' href="dashboard.php"><?php echo lang('Main'); ?></a>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active"><a class="nav-link" href="categories.php"><?php echo lang('Category'); ?></a></li>
        <li class="nav-item active"><a class="nav-link" href="#"><?php echo lang('ITEMS'); ?></a></li>
        <li class="nav-item active"><a class="nav-link" href="members.php"><?php echo lang('MEMBERS'); ?></a></li>
        <li class="nav-item active"><a class="nav-link" href="#"><?php echo lang('STATISTICS'); ?></a></li>
        <li class="nav-item active"><a class="nav-link" href="#"><?php echo lang('LOGS'); ?></a></li>
      </ul>
      <ul class="navbar-nav">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo $_SESSION['Username'] ?>
          </a>
          <div class="dropdown-menu bg-dark" aria-labelledby="navbarDropdown">
            <a class="dropdown-item text-light" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']; ?>">Edit Profile</a>
            <a class="dropdown-item text-light" href="#">Settings</a>
            <a class="dropdown-item text-light" href="logout.php">Logout</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>