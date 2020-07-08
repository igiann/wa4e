<?php
require_once "pdo.php";

session_start();

// Demand a GET parameter
if ( ! isset($_SESSION['name']) ) {
    die('ACCESS DENIED');
}

function validatePos() {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if ( strlen($year) == 0 || strlen($desc) == 0 ) {
          echo('pipa');
            return "All fields are required";
        }
        if ( ! is_numeric($year) ) {
            return "Position year must be numeric";
        }
    }
    return true;
}

if ( isset($_POST['first_name']) &&  isset($_POST['last_name']) && isset($_POST['email'])  && isset($_POST['headline']) && isset($_POST['summary'])) {
  // Data validation
      $msg = validatePos();
      if(is_string($msg)){
        $_SESSION['error'] = $msg;
        echo($msg);
        header("Location: add.php");
        return;
      }

  // email validation
  if ( strpos($_POST['email'],'@') === false ) {
      $_SESSION['error'] = 'Email address must contain @';
      header("Location: add.php");
      return;
  }

  $sql = "INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary)
          VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary )";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':user_id' => $_SESSION['user_id'],
    ':first_name' => $_POST['first_name'],
    ':last_name'=> $_POST['last_name'],
    ':email' => $_POST['email'],
    ':headline' => $_POST['headline'],
    ':summary' => $_POST['summary']));
  $_SESSION['success'] = 'Profile added';

  $profile_id = $pdo->lastInsertId();

    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare("INSERT INTO Position
            (profile_id, `rank`, year, description)
        VALUES ( :pid, :rank, :year, :desc)");
        $stmt->execute(array(
            ':pid'  => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc));
        $rank++;
    }
    $_SESSION['success'] = 'Profile added';
    header("Location: index.php");
    return;

    // Insert the Education entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare("INSERT INTO Position
            (profile_id, `rank`, year, description)
        VALUES ( :pid, :rank, :year, :desc)");
        $stmt->execute(array(
            ':pid'  => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc));
        $rank++;
    }
    $_SESSION['success'] = 'Profile added';
    header("Location: index.php");
    return;

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Joan Jani's Profile Add</title>
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
<h1>Adding Profile for UMSI</h1>
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
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<script>
countPos = 0;

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
