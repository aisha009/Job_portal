<?php
require_once "config.php";


$CNIC_NO=  $MOBILE_NUMBER = $EMAIL = $EMAIL_VERIFICATION_CODE= $FULL_NAME= $SURNAME = $COUNTRY = $DOMICILE_DISTRICT = $PASSWORD = $RE_TYPE_PASSWORD = $DOMICILE_PROVINCE_STATE= "";


if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["CNIC_NO"]))){
        $CNIC_NO_err = "CNIC_NO cannot be blank";
    }else{
      $CNIC_NO= trim($_POST['CNIC_NO']);
    }
    

// Check for password
if(empty(trim($_POST['CNIC_NO']))){
    $CNIC_NO_err = "CNIC_NO cannot be blank";
}
elseif(strlen(trim($_POST['PASSWORD'])) < 5){
    $PASSWORD_err = "PASSWORD cannot be less than 5 characters";
}
else{
    $PASSWORD = trim($_POST['PASSWORD']);
}

// Check for confirm password field
if(trim($_POST['RE_TYPE_PASSWORD']) !=  trim($_POST['RE_TYPE_PASSWORD'])){
    $RE_TYPE_PASSWORD_err = "RE_TYPE_PASSWORD should match";
}


$CNIC_NO = trim($_POST['CNIC_NO']);
$EMAIL = trim($_POST['EMAIL']);
$FULL_NAME = trim($_POST['FULL_NAME']);
$DOMICILE_DISTRICT = trim($_POST['DOMICILE_DISTRICT']);
$SURNAME = trim($_POST['SURNAME']);
$MOBILE_NUMBER=trim($_POST['MOBILE_NUMBER']);
$DOMICILE_PROVINCE_STATE=trim($_POST['DOMICILE_PROVINCE_STATE']);
$PASSWORD=trim($_POST['PASSWORD']);
$RE_TYPE_PASSWORD=trim($_POST['RE_TYPE_PASSWORD']);
$COUNTRY=trim($_POST['COUNTRY']);


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{


    $sql = "INSERT INTO user_registration (CNIC_NO,MOBILE_NO,EMAIL,FULL_NAME,SURNAME,COUNTRY,DOMICILE_DISTRICT,DOMICILE_PROVINCE_STATE ,PASSWORD,RE_TYPE_PASSWORD) VALUES ('".$CNIC_NO."','".$MOBILE_NUMBER."','".$EMAIL."','".$FULL_NAME."','".$SURNAME."','".$COUNTRY."','".$DOMICILE_DISTRICT."','".$DOMICILE_PROVINCE_STATE."','".$PASSWORD."','".$RE_TYPE_PASSWORD."')";

//echo $sql;
//exit();

 
    $stmt = mysqli_query($conn, $sql);
    if($stmt){
      header("Location: login.php");
    }else{
      exit("Something went wrong..");
    }
    
    
}else{
  exit("here!!");
}
mysqli_close($conn);
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
    <link rel="stylesheet" type="text/css" href="register.css">
    

</head>
    

<div class="container ">
<h2>register here</h2>
<form action="register.php" method="post">

  <div class="content">
<div class="inputbox">
    
    <label for="FULL_NAME">FULL_NAME</label>
    <input type="text"  name ="FULL_NAME"  placeholder=" enter FULL_NAME" required>
     

</div>      


<div class="inputbox">
    
    <label for="SURNAME">SURNAME</label>
    <input type="text"  name ="SURNAME"  placeholder=" enter surname" required>
     

</div>      

<div class="inputbox">
    
    <label for="CNIC_NO">CNIC_NO</label>
    <input type="CNIC_NO"  name ="CNIC_NO"  placeholder=" enter CNIC_NO" required>
     

</div>      

<div class="inputbox">
    
    <label for="MOBILE_NUMBER">MOBILE_NUMBER</label>
    <input type="text"  name ="MOBILE_NUMBER"  placeholder=" enter MOBILE_NUMBER" required>
     

</div>      

<div class="inputbox">
    
    <label for="COUNTRY">COUNTRY</label>
    <input type="text"  name ="COUNTRY"  placeholder=" enter COUNTRY" required>
     

</div>      



<div class="inputbox">
    
    <label for="EMAIL">EMAIL</label>
    <input type="EMAIL"  name ="EMAIL"  placeholder=" enter EMAIL" required>
     

</div>      




<div class="inputbox">
    
    <label for="DOMICILE_PROVINCE_STATE">DOMICILE_PROVINCE_STATE</label>
    <input type="text"  name ="DOMICILE_PROVINCE_STATE"  placeholder=" enter COUNTRY" required>
     

</div>      
<div class="inputbox">
    
    <label for="DOMICILE_DISTRICT">DOMICILE_DISTRICT</label>
    <input type="text"  name ="DOMICILE_DISTRICT"  placeholder=" enter COUNTRY" required>
     

</div>      

<div class="inputbox">
    
    <label for="PASSWORD">PASSWORD</label>
    <input type="password"  name ="PASSWORD"  placeholder=" enter COUNTRY" required>
     

</div>      

<div class="inputbox">
    
    <label for="RE_TYPE_PASSWORD">RE_TYPE_PASSWORD</label>
    <input type="password"  name ="RE_TYPE_PASSWORD"  placeholder=" enter COUNTRY" required>
     

</div>      


<span class="gender_title">GENDER</span>
<div class="gender_cat">
    
    <input type="radio" name="gender" id="male">
    <label for="male">male</label>
    <input type="radio" name="gender" id="female">
    <label for="female">female</label>


</div>
 <div class="button-container">
     
     <button>Sign Up</button>
 </div>

  </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
