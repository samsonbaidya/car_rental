<?php
require_once "oci.php";
session_start();
if(!isset($_SESSION['account']) || $_SESSION['role'] != 0){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if(isset($_POST['logout'])){
  header('Location:logout.php');
  return;
}

if(isset($_POST['edit-profile'])){
  header('Location:settings-user.php');
  return;
}

if(isset($_POST['change-password'])){
    header("Location: chpass-user.php");
    return;
}

try{

  $stmt = $pdo->query("SELECT * FROM available_cars");
  $rowa = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $stmt = $pdo->prepare("SELECT * FROM BOOKING");
  $stmt->execute(array(":customer_name" =>  $_SESSION['un']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);


  $stmt = $pdo->prepare("SELECT * FROM CUSTOMER WHERE CUSTOMER_NAME = :customer_name ");
  $stmt->execute(array(":customer_name" =>  $_SESSION['un']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);


}catch(Exception $ex){
  echo("Exception message: ". $ex->getMessage());
  header("Location: user-home.php");
  return;
}

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>user-home</title>
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
                <a class="nav-item nav-link" href="settings-user.php">Edit Profile <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="chpass-user.php"> Change Password </a>
                <a class="nav-item nav-link" href="logout.php"> LOGOUT </a>

              </div>
          </div>
          </nav>
    </div>
    <!-- <form method="post">
      <input type="submit" name="logout" value="Logout"><br><hr>
    </form> -->
    <h1>User Home Page</h1>

    <h2>Welcome
      <?php echo htmlentities($_SESSION['account']); ?>
    </h2>

    <form method="post">
      <input type="submit" name="edit-profile" value="Edit Profile">
      <input type="submit" name="change-password" value="Change Password"><br><hr>
    </form>

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

    <hr> <h4>Available Cars : </h4>
    <form method="POST">
      <table border="1">
        <?php
        if ($rowa == 0){
          echo("<p>No Car Available</p>");
        }
        else{
          echo "<tr><td style='text-align:center'>";
          echo "Car Id";
          echo("</td><td style='text-align:center'>");
          echo "Type";
          echo("</td><td style='text-align:center'>");
          echo "Color";
          echo("</td><td style='text-align:center'>");
          echo "No of seats";
          echo("</td><td style='text-align:center'>");
          echo "Mileage";
          echo("</td><td style='text-align:center'>");
          echo "Cost Per Day";
          echo("</td><td style='text-align:center'>");
          echo "Operations";

          foreach ( $rowa as $row ) {
            echo "<tr><td style='text-align:center'>";
            echo $row['CAR_ID'];
            echo("</td><td style='text-align:center'>");
            echo htmlentities($row['NAME']);
            echo("</td><td style='text-align:center'>");
            echo htmlentities($row['COLOR']);
            echo("</td><td style='text-align:center'>");
            echo htmlentities($row['NO_OF_SEATS']);
            echo("</td><td style='text-align:center'>");
            echo htmlentities($row['MILAGE']);
            echo("</td><td style='text-align:center'>");
            echo htmlentities($row['COST_PER_DAY']);
            echo("</td><td style='text-align:center'>");
            echo '<a href="book.php?car_id='.$row['CAR_ID'].'">Book</a> ';
            echo("</td></tr>\n");
          }
        }
        ?>

      </table><br><br><hr>
    </form>


    <hr> <h4>Booked Cars : </h4>
    <form method="post">

      <?php
      if($row === false){
        echo '<p>Book a car</p> ';
      }
      else{
        echo '<a href="return.php?car_id='.$row['CAR_ID'].'">Return Vehicle</a> ';
      }
       ?>
       <br><hr>
    </form>



  </body>
</html>
