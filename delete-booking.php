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
if ( !isset($_GET['booking_id']) ) {
    $_SESSION['error'] = "Missing User Id.";
    header('Location: admin-home.php');
    return;
}

if ( isset($_POST['Delete']) && isset($_POST['booking_id']) ) {
    // $sql = "DELETE FROM BILL WHERE BOOKING_ID = :booking_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));

    // $sql = "DELETE FROM DISCOUNT WHERE BOOKING_ID = :booking_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));


    // $sql = "DELETE FROM CUSTOMER WHERE BOOKING_ID = :booking_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));

    // $sql = "UPDATE CAR SET AVAILABILITY = 'yes' WHERE CAR_ID = (SELECT CAR_ID FROM CARBOOK WHERE BOOKING_ID = :booking_id)";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));

    // $sql = "DELETE FROM CARBOOK WHERE BOOKING_ID = :booking_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));


    // $sql = "DELETE FROM BOOKING WHERE BOOKING_ID = :booking_id";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute(array(':booking_id' => $_POST['booking_id']));

    $sql = "CREATE OR REPLACE PROCEDURE DELETE_BOOKING
                IS
                BEGIN
                    DELETE FROM BILL WHERE BOOKING_ID = '".$_POST['booking_id']."' ;
                    DELETE FROM DISCOUNT WHERE BOOKING_ID = '".$_POST['booking_id']."';
                    DELETE FROM CUSTOMER WHERE BOOKING_ID = '".$_POST['booking_id']."' ;
                    UPDATE CAR SET AVAILABILITY = 'yes' WHERE CAR_ID = (SELECT CAR_ID FROM CARBOOK WHERE BOOKING_ID = '".$_POST['booking_id']."');
                    DELETE FROM CARBOOK WHERE BOOKING_ID = '".$_POST['booking_id']."' ;
                    DELETE FROM BOOKING WHERE BOOKING_ID = '".$_POST['booking_id']."' ;
                END;";
      execute($sql);
      $sql  = "BEGIN DELETE_BOOKING; END;";
      execute($sql);

    $_SESSION['success'] = 'Booking deleted.';
    header( 'Location: admin-home.php?booking_id='.$_POST['booking_id']);
    return;
}

try {
  $sql = "SELECT * FROM BOOKING WHERE BOOKING_ID = :booking_id" ;
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':booking_id' => $_GET['booking_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      $_SESSION['error'] = 'Account Not Found.';
      header( 'Location: admin-home.php');
      return;
  }
} catch (Exception $ex) {
  echo("Exception message: ". $ex->getMessage());
  header('Location: delete.php?booking_id='.$_POST['booking_id']);
  return;
}

 ?>

 <!DOCTYPE html>
 <html lang="en" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>delete-booking</title>
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


       <h2>Delete Booking</h2>
       <p>
         <h5>Booking Information</h5>
       </p>
      <p>Booking Id : <?= htmlentities($row['BOOKING_ID']) ?></p>
      <p>Amount : <?= htmlentities($row['AMOUNT']) ?></p>
       <form method="post">
         <p>Confirm Delete
         <input type="submit" name="Delete" value="Delete"></p>
         <input type="hidden" name="booking_id" value="<?=$row['BOOKING_ID'] ?>">
       </form>

   </body>
 </html>
