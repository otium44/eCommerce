<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css">
  <link rel="stylesheet" href="<?php echo $css; ?>backend.css">
  <script src="https://kit.fontawesome.com/1e6ed09e58.js" crossorigin="anonymous"></script>
  <title><?php getTitle(); ?></title>
</head>
<body>
  <div class="upper-bar">
    Upper Bar
  </div>
<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-dark bg-dark">
  <div class="container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class='navbar-brand' href="index.php">Homepage</a>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class=" navbar-nav navbar-right ml-auto">
      <?php 
          foreach (getCat() as $cat) {

            echo '<li class="nav-item"><a class="nav-link" href="categories.php?pageid='. $cat['ID'] .'&pagename='. str_replace(' ', '-', $cat['Name']) .'">' . $cat['Name'] . '</a></li>';
        
          }
      ?>
      </ul>
    </div>
  </div>
</nav>