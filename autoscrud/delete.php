<?php
  require_once "pdo.php";

  session_start();

  // Demand a GET parameter
  if ( ! isset($_SESSION['email']) ) {
  		die("ACCESS DENIED");

  }

  if ( isset($_POST['delete']) && isset($_POST['autos_id']) ) {
      $sql = "DELETE FROM autos WHERE autos_id = :zip";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':zip' => $_POST['autos_id']));
      $_SESSION['success'] = 'Record deleted';
      header( 'Location: index.php' ) ;
      return;
  }

  // Guardian: Make sure that user_id is present
  if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
  }

  $stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['autos_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for autos_id';
      header( 'Location: index.php' ) ;
      return;
  }

?>
<!DOCTYPE html>
<html>
<head>
<title>Joan Jani</title>

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

</head>
<body>
<div class="container">

<p>Confirm: Deleting <?= htmlentities($row['make']) ?></p>
<form method="post"><input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">
<input type="submit" value="Delete" name="delete"><a href="index.php">Cancel</a>
</form>
</div>
</body>
