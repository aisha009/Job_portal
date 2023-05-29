<?php
require_once "config.php";

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location: Dashboard.php");
    exit;
}

$username = $password = "";
$err = "";

if($_SERVER['REQUEST_METHOD'] == "POST") {
  
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

if(empty($err)){
      // Prepare statement
    $stmt = mysqli_prepare($conn, "SELECT * FROM user_registration WHERE CNIC_NO = ? AND PASSWORD = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    // Check if there is a match in the database
    if(mysqli_stmt_num_rows($stmt) == 1) {
        // Start a new session
        session_start();
        
        // Store the user's information in a session
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect the user to the protected area
        header("Location: Dashboard.php");
        exit();
    } else {
        //echo "<h2 style='color: red'>Wrong username or password.</h2>";
        echo "<div class='alert alert-danger'>
        <strong>Message!</strong> Wrong username or password..
      </div>";
    }
    
    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
    

}




?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="over.style">
    <title>PHP login system!</title>
    
  </head>
  <body>


<form action="" method="post">
  <div class="login_in">
    <h1>login form</h1>
    <label>Username</label>
    <input type="text" name="username"  placeholder="Enter Username">

    <label >Password</label>
    <input type="password" name="password"  placeholder="Enter Password">
  
  <a href="login.php"> <button type="submit">login</button></a>
  
    <a href="register.php" class="reg">create new account</a>
</form>




    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
