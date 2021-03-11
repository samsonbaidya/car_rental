<?php
session_start();

if(isset($_POST['mainpage'])){
  header('Location:index.php');
  return;
}
if(isset($_SESSION['code'])){
    unset($_SESSION['code']);
}

$refer = 'ref00000';
if(isset($_POST['confirm'])){
  if(!isset($_POST['referenceno']) || strlen($_POST['referenceno'])<1){
    $_SESSION['error'] = "Type your reference number to goto signup page.";
    header('Location:conf-signup.php');
    return;
  }
  if( $refer !=  $_POST['referenceno'] ){
    $_SESSION['error'] = "Reference number must be matched";
    header('Location:conf-signup.php');
    return;
  }
  $_SESSION['success'] = "HR-Member Confirmed.";
  $_SESSION["code"] =  $_POST['referenceno'];
  header('Location:signup-admin.php');
  return;

}




 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>confirm signup</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
  <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   </head>
   <body style="margin:20px;margin-top:70px">

     <div class= "container">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="index.php">CAR RENTAL SYSTEM </a>
    <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">
        <a class="nav-item nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link" href="signup.php">Sign Up</a>
        <a class="nav-item nav-link" href="conf-signup.php">Sign Up as Admin</a>
        </div>
    </div>
    </nav>
  </div>

     <!-- <form method="POST">

       <input type="submit" name="mainpage" value="<< Main Page"><br>

     </form> -->

     <h1> HR-Member Confirmation </h1>

     <?php

       if ( isset($_SESSION["success"]) ) {
           echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
           unset($_SESSION["success"]);
       }
       else if ( isset($_SESSION["error"]) ) {
           echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
           unset($_SESSION["error"]);
       }
     ?>

     <form method="post">

       <label for="id_1723">Reference Number</label>
       <input type="text" name="referenceno" id="id_1723"><br/>
       <input type="submit" name="confirm" value="Confirm">

     </form>

     <p>You must know the <span style="font-weight: bold;"> Reference number</span> to Signup. Contact
     <span style="font-weight: bold;"> HR Members </span> if you don't. </p>
     <p>Signup option is only for <span style="font-weight: bold;"> HR Members.</span> Don't Signup if you are not HR Member. </p>

   </body>
 </html>
