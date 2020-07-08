<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_SESSION["account"]) ) { ?>
   <p>Please <a href="login.php">Log In</a> to start.</p>
<?php } else { ?>
   <p>This is where a cool application would be.</p>
   <p>Please <a href="logout.php">Log Out</a> when you are done.</p>
<?php }

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    session_start();
    session_destroy();
    header('Location: index.php');
    return;
}


$_SESSION["success"])  = false;
$_SESSION['error']  = false;

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani c9c41b10</title>
</head>
<div class="container">

<a href="add.php"> Add new </a>

<body>
<h1>Tracking Autos for <?= htmlentities($_GET['name'])?></h1>
<?php
if ( $_SESSION["success"]) !== false )
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION["success"])."</p>\n");

if ( $_SESSION['error'] !== false )
	echo('<p style="color: green;">'.htmlentities($_SESSION['error'])."</p>\n");
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
</body>
</html>
