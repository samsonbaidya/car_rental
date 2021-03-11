<?php
session_start();
require_once "oci.php";
if(!isset($_SESSION['account']) || $_SESSION['role'] != 1){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}
if ( isset($_POST['home']) ) {
    header('Location: admin-home.php');
    return;
}



if(isset($_POST['Add'])){
  if(isset($_POST['cat_id']) && isset($_POST['color']) && isset($_POST['mileage']) && isset($_POST['no_of_seats']) && isset($_POST['cost'])){
    if(strlen($_POST['cat_id']) < 1 || strlen($_POST['color']) < 1 || strlen($_POST['mileage']) < 1  || strlen($_POST['no_of_seats']) < 1 || strlen($_POST['cost']) < 1){
      $_SESSION["error"] = "All field required";
      header("Location: add-car.php");
      return;
    }
    if(!is_numeric($_POST['mileage'])){
      $_SESSION["error"] ="Mileage must be a number";
      header("Location: add-car.php");
      return;
    }
    if(!is_numeric($_POST['no_of_seats'])){
      $_SESSION["error"] ="No of seats must be a number";
      header("Location: add-car.php");
      return;
    }
    if(!is_numeric($_POST['cost'])){
      $_SESSION["error"] ="Cost must be a number";
      header("Location: add-car.php");
      return;
    }
    try{
      $stmt = $pdo->prepare("SELECT * FROM CATEGORY WHERE CATEGORY_ID = :cat_id ");
      $stmt->execute(array(":cat_id" => $_POST['cat_id'] ));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $sql = "INSERT INTO CAR (CAR_ID, AVAILABILITY, COLOR, MILAGE)
                          VALUES (CAR_ID.NEXTVAL, :avail, :color, :mile)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':avail' => 'yes',
          ':color' => $_POST['color'],
          ':mile' => $_POST['mileage']
        ));

      $sql = "INSERT INTO CATEGORY (CATEGORY_ID, NO_OF_SEATS, NAME, COST_PER_DAY,CAR_ID)
                          VALUES ( :cat_id, :no_of_seats, :name, :cost, CAR_ID.CURRVAL)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':cat_id' =>$row['CATEGORY_ID'],
          ':no_of_seats' => $_POST['no_of_seats'],
          ':name' => $row['NAME'],
          ':cost' => $_POST['cost']

        ));
      $_SESSION["success"] = "Car Added Successfully.";
      header("Location: admin-home.php");
      return;
    }catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
      $_SESSION["error"] = "Lol.";
      header("Location: add-car.php");
      return;
    }
}else{
  $_SESSION['error'] = "All fields are Required.";
  header('Location:add-car.php');
  return;
}

}

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>add-car</title>
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

<h1>Add New Car </h1>


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

  <p>CATEGORY_ID:
  <input type="text" name="cat_id" size="60"/></p>
  <p>Color:
  <input type="text" name="color" /></p>
  <p>Mileage:
  <input type="text" name="mileage" /></p>
  <p>No of seats:
  <input type="text" name="no_of_seats" /></p>
  <p>Cost:
  <input type="text" name="cost" /></p>
  <input type="submit" name="Add" value="Add">
  </form>

</body>
</html>
