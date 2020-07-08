<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Joan Jani c9c41b10</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body style="font-family: sans-serif;">
<div class="container">
  <h1>Welcome to Autos Database</h1>
<?php
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }

    // Check if we are logged in!
    if ( ! isset($_SESSION["account"]) ) { ?>
      <a href="login.php">Please Log In</a>
      </p>
      <p>
      Attempt to go to
      <a href="view.php">view.php</a> without logging in - it should fail with an error message.
      </p>
      <p>
      Attempt to go to
      <a href="add.php">add.php</a> without logging in - it should fail with an error message.
      </p>
      <p>
      <a href="https://www.wa4e.com/assn/autosess/" target="_blank">Specification for this Application</a>
      </p>
    <?php } else { ?>
       <p>This is where a cool application would be.</p>
       <p>Please <a href="logout.php">Log Out</a> when you are done.</p>
    <?php } ?>
</body>
</html>
