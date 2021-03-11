<?php
require_once "oci.php";
require_once "conn.php";
session_start();
if(!isset($_SESSION['account']) || $_SESSION['role'] != 1){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}
// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}
if ( isset($_POST['home']) ) {
    header('Location: admin-home.php');
    return;
}
if ( !isset($_GET['car_id']) ) {
    $_SESSION['error'] = "Missing User Id.";
    header('Location: admin-home.php');
    return;
}

if ( isset($_POST['Delete']) && isset($_POST['car_id']) ) {

  $sql = "CREATE OR REPLACE PROCEDURE DELETE_CATEGORY
              IS
              BEGIN
                  DELETE FROM CATEGORY WHERE CAR_ID = '".$_POST['car_id']."' ;
              END;";
  execute($sql);

  // $sql = "DELETE FROM CATEGORY WHERE CAR_ID = :car_id";
  // $stmt = $pdo->prepare($sql);
  // $stmt->execute(array(':car_id' => $_POST['car_id']));


  $sql = "CREATE OR REPLACE PROCEDURE DELETE_CAR
              IS
              BEGIN
                  DELETE FROM CAR WHERE CAR_ID = '".$_POST['car_id']."' ;
              END;";
  execute($sql);
  $sql  = "begin DELETE_CAR; DELETE_CATEGORY; end;";
  execute($sql);

  // $sql = "DELETE FROM CAR WHERE CAR_ID = :car_id";
  // $stmt = $pdo->prepare($sql);
  // $stmt->execute(array(':car_id' => $_POST['car_id']));

    $_SESSION['success'] = 'Car deleted';
    header( 'Location: admin-home.php?car_id='.$_POST['car_id']);
    return;
}

try {
  $sql = "SELECT * FROM CAR,CATEGORY WHERE CAR.CAR_ID = :car_id AND CAR.CAR_ID=CATEGORY.CAR_ID" ;
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':car_id' => $_GET['car_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Car Not Found.';
      header( 'Location: admin-home.php');
      return;
  }
} catch (Exception $ex) {
  echo("Exception message: ". $ex->getMessage());
  header('Location: delete-car.php?car_id='.$_POST['car_id']);
  return;
}

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>delete-car</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
  <!-- CSS only -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

   </head>
   <body style="margin:20px;margin-top:70px">


       <div class="container">
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="background-color: #e3f2fd;">
             <a class="navbar-brand" href="#">CAR RENTAL SYSTEM </a>
             <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
                 <div class="navbar-nav ml-auto">
                 <a class="nav-item nav-link" href=" admin-home.php"> BACK <span class="sr-only">(current)</span></a>
                 <a class="nav-item nav-link" href="logout.php"> LOGOUT </a>

                 </div>
             </div>
             </nav>
       </div>
       <!-- <form method="post">
         <input type="submit" name="logout" value="Logout"><br><hr>
           <input type="submit" name="home" value=" << Home ">
       </form> -->

       <h2>Delete Car</h2>
       <p>
         <h5>Car Information</h5>
       </p>
      <p>Car Id : <?= htmlentities($row['CAR_ID']) ?></p>
      <p>Car Color : <?= htmlentities($row['COLOR']) ?></p>
      <p>Car Catagory : <?= htmlentities($row['NAME']) ?></p>
      <p>Catagory Id : <?= htmlentities($row['CATEGORY_ID']) ?></p>
       <form method="post">
         <p>Confirm Delete
         <input type="submit" name="Delete" value="Delete"></p>
         <input type="hidden" name="car_id" value="<?=$row['CAR_ID'] ?>">
       </form>



   </body>
 </html>
