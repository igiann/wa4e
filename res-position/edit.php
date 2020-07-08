<?php
  require_once "pdo.php";

  session_start();

  // Demand a GET parameter
  if ( ! isset($_SESSION['name']) ) {
      die("ACCESS DENIED");
  }

  if ( isset($_POST['first_name']) &&  isset($_POST['last_name']) && isset($_POST['email'])  && isset($_POST['headline']) && isset($_POST['summary'])) {
    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
      $_SESSION['error'] = 'All values are required';
      header("Location: add.php");
      return;
    }

    // email validation
    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: add.php");
        return;
    }

    $sql = "UPDATE Profile SET user_id = :user_id, first_name = :first_name,
            last_name = :last_name, email = :email, headline = :headline,
            summary = :summary WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':user_id' => $_SESSION['user_id'],
      ':first_name' => $_POST['first_name'],
      ':last_name'=> $_POST['last_name'],
      ':email' => $_POST['email'],
      ':headline' => $_POST['headline'],
      ':summary' => $_POST['summary'],
      ':profile_id' => $_POST['profile_id']));


    // Clear out the old position entries
   $stmt = $pdo->prepare('DELETE FROM Position
       WHERE profile_id=:pid');
   $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

   // Insert the position entries
   $rank = 1;
   for($i=1; $i<=9; $i++) {
       if ( ! isset($_POST['year'.$i]) ) continue;
       if ( ! isset($_POST['desc'.$i]) ) continue;
       $year = $_POST['year'.$i];
       $desc = $_POST['desc'.$i];

       $stmt = $pdo->prepare('INSERT INTO Position
           (profile_id, `rank`, year, description)
       VALUES ( :pid, :rank, :year, :desc)');
       $stmt->execute(array(
           ':pid' => $_REQUEST['profile_id'],
           ':rank' => $rank,
           ':year' => $year,
           ':desc' => $desc)
       );
       $rank++;
   }
   $_SESSION['success'] = 'Profile updated';
   header("Location: index.php");
   return;

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
      $_SESSION['error'] = 'Bad value for user_id';
      header( 'Location: index.php' ) ;
      return;
  }


  // Flash pattern
  if ( isset($_SESSION['error']) ) {
      echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
      unset($_SESSION['error']);
  }

  $stmt2 = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
  $stmt2->execute(array(":xyz" => $_GET['profile_id']));

  $fn = htmlentities($row['first_name']);
  $ln = htmlentities($row['last_name']);
  $em = htmlentities($row['email']);
  $hl = htmlentities($row['headline']);
  $sm = htmlentities($row['summary']);
  $profile_id= $row['profile_id'];



 ?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<!-- Optional theme -->
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
<h1>Editing Profile for UMSI</h1>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $em ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $hl ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"> test</textarea>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>" />
<p> Position: <input type="submit" id="addPos" value="+">
  <div id="position_fields">


<?php
  $count=0;
  while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
    echo('<div id="position'.htmlentities($row2['rank']).'">');
    echo('<p>Year: <input type="text" name="year'.htmlentities($row2['rank']).'" value="'.htmlentities($row2['year']).'" />');
    echo('<input type="button" value="-"onclick="$(\'#position'.htmlentities($row2['rank']).'\').remove();return false;">');
    echo('</p>');
    echo('<textarea name="desc'.htmlentities($row2['rank']).'" rows="8" cols="80">');
    echo(htmlentities($row2['description']));
    echo('</textarea>');
    echo('</div>');

    $count++;
  }
?>
</div></p>

<p>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>

</form>
<script>
countPos = <?php echo($count); ?>;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>
</div>
</body>
</html>
