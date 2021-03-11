<?php
require_once "oci.php";
session_start();
if(!isset($_SESSION['account']) || $_SESSION['role'] != 1){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if(isset($_POST['logout'])){
  header('Location:logout.php');
  return;
}
if(isset($_POST['add-book'])){
  header('Location:add-book.php');
  return;
}
if(isset($_POST['add-car'])){
  header('Location:add-car.php');
  return;
}
if(isset($_POST['profile'])){
  header('Location:settings-admin.php');
  return;
}
if(isset($_POST['change-password'])){
    header("Location: chpass-admin.php");
    return;
}


if(isset($_POST['clear'])){
  try{
    $sql = "UPDATE USER SET paid_status = :status WHERE role = :role ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':status' => 0,
        ':role' => 0 ));
    $_SESSION["success"] = "All Payment status has been cleared.";
    header("Location: admin-home.php");
    return;
  }catch(Exception $ex){
    echo("Exception message: ". $ex->getMessage());
    header("Location: admin-home.php");
    return;
  }

}
if(isset($_POST['clear-transactions'])){
  try{
    $stmt = $pdo->query("DELETE FROM PAYMENT");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION["success"] = "All Payment status has been cleared.";
    header("Location: admin-home.php");
    return;
  }catch(Exception $ex){
    echo("Exception message: ". $ex->getMessage());
    header("Location: admin-home.php");
    return;
  }

}

if(isset($_POST['set'])){
  try{
    $sql = "UPDATE USER SET paid_status = :status WHERE role = :role ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':status' => 1,
        ':role' => 0 ));
    $_SESSION["success"] = "All Payment status has been set.";
    header("Location: admin-home.php");
    return;
  }catch(Exception $ex){
    echo("Exception message: ". $ex->getMessage());
    header("Location: admin-home.php");
    return;
  }

}


try{

  $stmt = $pdo->query("SELECT * FROM customer_view ORDER BY BOOKING_ID");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $pdo->query("SELECT * FROM BOOKING ORDER BY BOOKING_ID");
  $rowt = $stmt->fetchAll(PDO::FETCH_ASSOC);


  $stmt = $pdo->query("SELECT * FROM CAR, CATEGORY WHERE CAR.CAR_ID = CATEGORY.CAR_ID");
  $rowa = $stmt->fetchAll(PDO::FETCH_ASSOC);


  // $stmt = $pdo->query("SELECT * FROM USER WHERE role = 0 AND paid_status = 0
  //                                 ORDER BY email");
  // $rowup = $stmt->fetchAll(PDO::FETCH_ASSOC);

}catch(Exception $ex){
  echo("Exception message: ". $ex->getMessage());
  header("Location: admin-home.php");
  return;
}


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>admin-home</title>
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
            <a class="nav-item nav-link" href="settings-admin.php">Edit Profile <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="chpass-admin.php"> Change Password </a>
            <a class="nav-item nav-link" href="logout.php"> LOGOUT </a>
            </div>
        </div>
        </nav>
  </div>
    <h1>Admin Home Page</h1>

    <h2>Welcome
      <?php echo htmlentities($_SESSION['account']); ?>
    </h2>

    <!-- <form method="post">
      <input type="submit" name="profile" value="Edit Profile">
      <input type="submit" name="change-password" value="Change Password"><br><hr>
    </form> -->

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

  <hr> <h4>Customer Details: </h4>
  <form method="POST">
    <table border="1">
      <?php
          echo "<tr><td style='text-align:center'>";
          echo "Name";
          echo("</td><td style='text-align:center'>");
          echo "Address";
          echo("</td><td style='text-align:center'>");
          echo "Phone";
          echo("</td><td style='text-align:center'>");
          echo "Booking Id";
          echo("</td><td style='text-align:center'>");
          echo "Car Id";
        foreach ( $rows as $row ) {
          echo "<tr><td style='text-align:center'>";
          echo $row['CUSTOMER_NAME'];
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['ADDRESS']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['PHONE']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['BOOKING_ID']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['CAR_ID']);
          echo("</td></tr>\n");
      }
      ?>

    </table><br><br><hr>
  </form>

  <hr> <h4>Booking Details : </h4>
  <form method="POST">
    <input type="submit" name="add-book" value="Add Booking"><br><br>
    <table border="1">
      <?php
          echo "<tr><td style='text-align:center'>";
          echo "Booking Id";
          echo("</td><td style='text-align:center'>");
          echo "Rent Date";
          echo("</td><td style='text-align:center'>");
          echo "Return Date";
          echo("</td><td style='text-align:center'>");
          echo "Date of Returned";
          echo("</td><td style='text-align:center'>");
          echo "Amount";
          echo("</td><td style='text-align:center'>");
          echo "Operations";
        foreach ( $rowt as $row ) {
          echo "<tr><td style='text-align:center'>";
          echo $row['BOOKING_ID'];
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['RENT_DATE']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['RETURN_DATE']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['DATE_OF_RETURNED']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['AMOUNT']);
          echo("</td><td style='text-align:center'>");
          echo '<a href="edit-booking.php?booking_id='.$row['BOOKING_ID'].'">Edit Booking Info</a> / ';
          echo '<a href="delete-booking.php?booking_id='.$row['BOOKING_ID'].'">Delete Booking</a>';
          echo("</td></tr>\n");
      }
      ?>

    </table><br><br><hr>
  </form>


  <hr> <h4>All Cars : </h4>
  <form method="post">
  <input type="submit" name="add-car" value="Add Car"><br><br>
    <table border="1">
      <?php
          echo "<tr><td style='text-align:center'>";
          echo "Car Id";
          echo("</td><td style='text-align:center'>");
          echo "Availability";
          echo("</td><td style='text-align:center'>");
          echo "Color";
          echo("</td><td style='text-align:center'>");
          echo "Mileage";
          echo("</td><td style='text-align:center'>");
          echo "Category Id";
          echo("</td><td style='text-align:center'>");
          echo "No of seats";
          echo("</td><td style='text-align:center'>");
          echo "Type";
          echo("</td><td style='text-align:center'>");
          echo "Cost per Day";
          echo("</td><td style='text-align:center'>");
          echo "Operations";
      foreach ( $rowa as $row ) {
          echo "<tr><td style='text-align:center'>";
          echo $row['CAR_ID'];
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['AVAILABILITY']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['COLOR']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['MILAGE']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['CATEGORY_ID']);
          echo("</td><td style='text-align:center'>");
          echo htmlentities($row['NO_OF_SEATS']);
          echo("</td><td style='text-align:center'>");
          echo $row['NAME'];
          echo("</td><td style='text-align:center'>");
          echo $row['COST_PER_DAY'];
          echo("</td><td style='text-align:center'>");
          echo '<a href="edit-car.php?car_id='.$row['CAR_ID'].'">Edit Car Info</a> / ';
          echo '<a href="delete-car.php?car_id='.$row['CAR_ID'].'">Delete Car</a>';
          echo("</td></tr>\n");
      }
      ?>

    </table><br><br><hr>
  </form>


  </body>
</html>
