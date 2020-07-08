<?php
    session_start();
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    $salt = 'XyZzy12*_';

    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

    // Check to see if we have some POST data, if we do process it
    if ( isset($_POST['email']) && isset($_POST['pass']) ) {
        if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
            $_SESSION['error']= "User name and password are required";
            error_log($_SESSION['error']);
        } else {
        	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
     			 $_SESSION['error'] = "Email must have an at-sign (@)";
           error_log($_SESSION['error']);
    		}else{

    		    $check = hash('md5', $salt.$_POST['pass']);
    		    if ( $check == $stored_hash ) {
    		        // Redirect the browser to game.php
                $_SESSION["email"] = $_POST["email"];
                $_SESSION["success"] = "Logged in.";
                error_log("Login success ".$_SESSION['email']);
                header("Location: index.php");
                return;
    		    } else {
    		       $_SESSION['error']= "Incorrect password";
               error_log($_SESSION['error']);
    		    }
    		 }
        }
    }
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
<h1>Please Log In</h1>
<?php
    if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
    }
?>
<form method="POST" action="login.php">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find an email and password hint
in the HTML comments.
<!-- Hint:
The email is csev@umich.edu
The password is the three character name of the
programming language used in this class (all lower case)
followed by 123. -->
</p>
</div>
</body>
