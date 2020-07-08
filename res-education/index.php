<?php
  require_once "pdo.php";
  session_start();
  $stmt = $pdo->query("SELECT profile_id,first_name,last_name, headline FROM Profile");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
 ?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani c11a6c88</title>
<!-- bootstrap.php - this is HTML -->

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
<h1>Joan Jani's Resume Registry</h1>

<?php
// Check if we are logged in!
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if ( ! isset($_SESSION["name"]) ) {
  echo('<p><a href="login.php">Please log in</a></p>');
  echo('<table border="1">'."\n");
  echo('<tr><th>Name</th><th>Headline</th><tr>');
  foreach ( $rows as $row ) {
      echo "<tr><td>";
      echo(htmlentities($row['first_name']));
      echo("</td><td>");
      echo(htmlentities($row['headline']));
      echo("</td></tr>\n");
    }
    echo('</table>');
} else { ?>
<p><a href="logout.php">Logout</a></p>
<?php
echo('<table border="1">'."\n");
echo('<tr><th>Name</th><th>Headline</th><th>Action</th><tr>');
foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo('<a href="view.php?profile_id='.$row['profile_id'].'">');
    echo(htmlentities($row['first_name']).' ');
    echo(htmlentities($row['last_name']).' ');
    echo('</a> ');
    echo "</td><td>";
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
    echo("</td></tr>\n");
  }
  echo('</table>');
  echo('<p><a href="add.php">Add New Entry</a></p>');
?>
<?php } ?>
<p><b>Note:</b> Your implementation should retain data across multiple
  logout/login sessions.  This sample implementation clears all its
  data on logout - which you should not do in your implementation.</p>
</div>
</body>
