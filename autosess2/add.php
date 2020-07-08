<?php
  require_once "pdo.php";

  session_start();

  // Demand a GET parameter
  if ( ! isset($_SESSION['email']) ) {
      die('Not logged in');
  }

  unset($_SESSION['error']);
  unset($_SESSION['success']);

  if (empty($_POST['make'])) {
  	$_SESSION['error'] = 'All values are required';
  }else {
  	if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

  		if(is_numeric($_POST['year']) && is_numeric($_POST['mileage'])){

        $_SESSION['make'] = $_POST['make'];
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['mileage'] = $_POST['mileage'];

  			$sql = "INSERT INTO autos (make, year, mileage)
  				      VALUES (:make, :year, :mileage)";
  			$stmt = $pdo->prepare($sql);

  			$stmt->execute(array(
  				':make' => $_SESSION['make'],
  				':year' => $_SESSION['year'],
  				':mileage' => $_SESSION['mileage']));
  			$_SESSION['success'] = 'Record inserted';
        header("Location: view.php");
        return;
  		}else{
  			$_SESSION['error']  = 'Mileage and year must be numeric';
  		     }
  		}
  }
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
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>
</ul>
</div>
</html>
