<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    session_start();
    session_destroy();
    header('Location: index.php');
    return;
}


$failure = false;
$registration = false;
$numerik = false;

if (empty($_POST['make'])) {
	$failure = "Make is required";
}else {
	if ( isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
		if(is_numeric($_POST['year']) && is_numeric($_POST['mileage'])){

			$sql = "INSERT INTO autos (make, year, mileage)
				      VALUES (:make, :year, :mileage)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':make' => $_POST['make'],
				':year' => $_POST['year'],
				':mileage' => $_POST['mileage']));
			$registration = 'Record inserted';
		}else{
			$numerik = 'Mileage and year must be numeric';
		     }
		}
}
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani c9c41b10</title>
</head>
<div class="container">

<body>
<h1>Tracking Autos for <?= htmlentities($_GET['name'])?></h1>
<?php
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
if ( $numerik !== false )
    echo('<p style="color: red;">'.htmlentities($numerik)."</p>\n");

if ( $registration !== false )
	echo('<p style="color: green;">'.htmlentities($registration)."</p>\n");
?>


<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

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
</div>
</body>
</html>
