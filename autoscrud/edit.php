<?php
  require_once "pdo.php";

  session_start();

  // Demand a GET parameter
  if ( ! isset($_SESSION['email']) ) {
  		die("ACCESS DENIED");

  }

  $stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['autos_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for user_id';
      header( 'Location: index.php' ) ;
      return;
  }


  if ( isset($_POST['make'])&& isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if(is_numeric($_POST['year']) && is_numeric($_POST['mileage'])){
          $sql = "UPDATE autos SET make = :make,
                  model = :model, year = :year, mileage = :mileage
                  WHERE autos_id = :autos_id";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(array(
              ':make' => $_POST['make'],
              ':model' => $_POST['model'],
              ':year' => $_POST['year'],
              ':mileage' => $_POST['mileage'],
              ':autos_id' => $_GET['autos_id']));
          $_SESSION['success'] = 'Record edited';
          header( 'Location: index.php' ) ;
          return;
    }else{
      $_SESSION['error']  = 'Mileage and year must be numeric';
    }
  }

  $mk = htmlentities($row['make']);
  $mdl= htmlentities($row['model']);
  $yr = htmlentities($row['year']);
  $ml = htmlentities($row['mileage']);
  $autos_id = $row['autos_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?= htmlentities($_SESSION["email"]) ?> </h1>
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
<form method="post">
<p>Make:

<input type="text" name="make" size="40" value="<?= $mk ?>" /></p>
<p>Model:

<input type="text" name="model" size="40" value="<?= $mdl ?>" /></p>
<p>Year:

<input type="text" name="year" size="10" value="<?= $yr ?>" /></p>
<p>Mileage:

<input type="text" name="mileage" size="10" value="<?= $ml ?>" /></p>
<input type="hidden" name="auto_id" value="<?= $auto_id ?>">

<input type="submit" name='add' value="Save">
<input type="submit" name="cancel" value="Cancel">
</form>
</ul>
</div>
</html>
