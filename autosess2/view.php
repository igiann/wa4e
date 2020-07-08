<?php
  require_once "pdo.php";

  session_start();

  // Demand a GET parameter
  if ( ! isset($_SESSION['email']) ) {
      die('Not logged in');
  }

  $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani c9c41b10</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_SESSION["email"]) ?></h1>
<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

?>
<h2>Automobiles</h2>
<ul>
  <?php
  foreach ( $rows as $row ) {
      echo "<li>";
      echo(htmlentities($row['year']));
      echo " ";
      echo(htmlentities($row['make']));
      echo " / ";
      echo(htmlentities($row['mileage']));
      echo("</li>");
  }
  ?>

</ul>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
</html>
