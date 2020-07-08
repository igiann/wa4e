<?php
  require_once "pdo.php";
  session_start();
  // Demand a GET parameter
  if ( ! isset($_SESSION['name']) ) {
      die("ACCESS DENIED");
  }

  // Guardian: Make sure that user_id is present
  if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
  }

  $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['profile_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header( 'Location: index.php' ) ;
      return;
  }

  $stmt2 = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
  $stmt2->execute(array(":xyz" => $_GET['profile_id']));

//  if ( $row2 === false ) {
//      $_SESSION['error'] = 'No position found';
//      header( 'Location: index.php' ) ;
//      return;
//  }

 ?>

<!DOCTYPE html>
<html>
<head>
<title>Dr. Chuck's Profile View</title>
<!-- head.php -->

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

</head>
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name: <?= htmlentities($row['first_name'])?></p>
<p>Last Name: <?= htmlentities($row['last_name'])?></p>
<p>Email: <?= htmlentities($row['email'])?></p>
<p>Headline:<br/><?= htmlentities($row['headline'])?></p>
<p>Summary:<br/> <?= htmlentities($row['summary'])?></p>
<p>Position</p><ul>
<?php
  while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
    echo('<li>');
    echo( $row2['year'].' : '.$row2['description']);
    echo('</li>');
  }
?>
</ul>
<p>
<a href="index.php">Done</a>
</p>
</div>
</body>
</html>
