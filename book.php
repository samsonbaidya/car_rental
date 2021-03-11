<?php
require_once "oci.php";
require_once "conn.php";
session_start();
if(!isset($_SESSION['account'])){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if(!isset($_GET['car_id'])){
  $_SESSION['error'] = "Car id not found.";
  header('Location:user-home.php');
  return;
}

if(isset($_POST['back'])){
  header('Location:user-home.php');
  return;
}




//
// if(isset($_POST['cupon']) && strlen($_POST['cupon']) > 1){
//   try {
//     $sql = "SELECT * FROM DISCOUNT WHERE NAME = :cupon";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute(array(':cupon' => $_POST['cupon']));
//     $rowd = $stmt->fetch(PDO::FETCH_ASSOC);
//     if($rowd === false){
//         $_SESSION["error"] = "Cupon not matched";
//         header("Location: book.php?car_id=".$_GET['car_id']);
//         return;
//     }
//
//     $sql = "INSERT INTO DISCOUNT (DISCOUNT_ID, NAME, TYPE, PERCENTAGE, BOOKING_ID)
//                         VALUES (DISCOUNT_ID.NEXTVAL,:name,:type, :percent,:b_id )";
//     $stmt = $pdo->prepare($sql);
//
//     $stmt->execute(array(
//         ':name' => $_POST['cupon'],
//         ':type' => $rowd['TYPE'],
//         ':percent' => $rowd['PERCENTAGE'],
//         ':b_id' => $rowd['BOOKING_ID']
//         ));
//     return;
//   } catch (Exception $e) {
//     echo("Exception message: ". $ex->getMessage());
//     $_SESSION["error"] = "Lol .";
//     header("Location: book.php?car_id=".$_GET['car_id']);
//     return;
//   }
// }



if(isset($_POST['book']) && isset($_POST['from']) && isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['to'])){
  if ( strlen($_POST['from']) < 1 || strlen($_POST['name']) < 1 || strlen($_POST['address']) < 1 || strlen($_POST['phone']) < 1 || strlen($_POST['to']) < 1 ) {
        $_SESSION["error"] = "All fields are required";
        header("Location: book.php?car_id=".$_GET['car_id']);
        return;
  }
  if(!is_numeric($_POST['phone'])){
    $_SESSION["error"] ="Phone number must be a number";
    header("Location: add-car.php");
    return;
  }
  try{
    $sql = "SELECT * FROM CUSTOMER WHERE CUSTOMER_NAME = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(  ':name' =>   $_SESSION['un'] ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == true){
        $_SESSION["error"] = "You cannot book untill you return previous vehicle. ";
        header("Location: book.php?car_id=".$_GET['car_id']);
        return;
    }

    $sql = "SELECT * FROM CUSTOMER WHERE PHONE = :ph";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(  ':ph' =>   $_POST['phone'] ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if($row == true){
        $_SESSION["error"] = "Your phone number is already used for another booking please insert another phone number. ";
        header("Location: book.php?car_id=".$_GET['car_id']);
        return;
    }



    // $from = date('m/d/Y', strtotime($_POST['from']));
    // $to = date('m/d/Y', strtotime($_POST['to']));
    // $name = $_POST['name'];
    // $phone = $_POST['phone'];
    // $address = $_POST['address'];
    // $car_id = $_GET['car_id'];
    // $sql = "CALL DO_BOOKING($name,$phone,$address,$from,$to); ";
    // $q = $pdo->query($sql);
    // $_SESSION["success"] = "Car booked successfully.";
    // header("Location: user-home.php");
    // return;


    $from = date('m/d/Y', strtotime($_POST['from']));
    $to = date('m/d/Y', strtotime($_POST['to']));
    $sql = "INSERT INTO BOOKING (BOOKING_ID, RENT_DATE, RETURN_DATE, DATE_OF_RETURNED, AMOUNT)
                        VALUES (BOOKING_ID.NEXTVAL,to_date(:rent,'mm/dd/yyyy'),to_date(:return,'mm/dd/yyyy'), to_date(:returned,'mm/dd/yyyy'), :amount)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array(
        ':rent' =>   $from,
        ':return' => $to,
        ':returned' => $to,
        ':amount' => 0
        ));

    $sql = "INSERT INTO CARBOOK (CARBOOK_ID, BOOKING_ID, CAR_ID)
                        VALUES (CARBOOK_ID.NEXTVAL,BOOKING_ID.CURRVAL,:car_id)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute(array(

        ':car_id' => $_GET['car_id']
        ));

    $sql = "INSERT INTO CUSTOMER (CUSTOMER_ID, CUSTOMER_NAME, ADDRESS, PHONE, BOOKING_ID, CAR_ID)
                        VALUES (CUSTOMER_ID.NEXTVAL, :name, :address, :phone, BOOKING_ID.CURRVAL, :car_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':name' => $_POST['name'],
        ':address' =>  $_POST['address'],
        ':phone' =>  $_POST['phone'],
        ':car_id' => $_GET['car_id']
        ));

    $sql = "UPDATE  CAR SET AVAILABILITY = 'no' WHERE CAR_ID = :car_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':car_id' => $_GET['car_id']
        ));
  }catch(Exception $ex){
    echo("Exception message: ". $ex->getMessage());
    $_SESSION["error"] = "Lol 12.";
    header("Location: book.php?car_id=".$_GET['car_id']);
    return;
  }



  if(isset($_POST['cupon']) && strlen($_POST['cupon']) > 1){
    try {

      $sql = "BEGIN
                IF SAMSONS_PACKAGE.IS_DISCOUNT('".$_POST['cupon']."') = TRUE THEN
                  DBMS_OUTPUT.PUT_LINE('You get Discount.!!!') ;
                ELSE
                  DBMS_OUTPUT.PUT_LINE('Invalid Cupon.') ;
                END IF;
              END;";

      $data= getPlSqlData($sql);

      // $sql = "SELECT * FROM DISCOUNT WHERE NAME = :cupon";
      // $stmt = $pdo->prepare($sql);
      // $stmt->execute(array(':cupon' => $_POST['cupon']));
      // $rowd = $stmt->fetch(PDO::FETCH_ASSOC);
      // if($rowd === false){
      //     $_SESSION["error"] = "Cupon not matched";
      //     header("Location: book.php?car_id=".$_GET['car_id']);
      //     return;
      // }

    } catch (Exception $e) {
      echo("Exception message: ". $ex->getMessage());
      $_SESSION["error"] = "Lol .";
      header("Location: book.php?car_id=".$_GET['car_id']);
      return;
    }
  }

  try{
    $sql = "SELECT * FROM CARBOOK WHERE CAR_ID = :c_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(  ':c_id' =>   $_GET['car_id']));
    $rowcc = $stmt->fetch(PDO::FETCH_ASSOC);


    $sql = "BEGIN
              SAMSONS_PACKAGE.TOTAL_COST('".$rowcc['BOOKING_ID']."');
            END;";

    execute($sql);


    $_SESSION["success"] = "Car booked successfully.".$data;
    header("Location: user-home.php");
    return;


  }catch(Exception $ex){
    echo("Exception message: ". $ex->getMessage());
    header("Location: user-home.php");
    return;
  }
}



try{

  $stmt = $pdo->query("SELECT * FROM available_cars");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>book</title>
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
          <a class="nav-item nav-link" href=" user-home.php"> BACK <span class="sr-only">(current)</span></a>
          <a class="nav-item nav-link" href="logout.php"> LOGOUT </a>

          </div>
      </div>
      </nav>
</div>
    <!-- <form method="post">
      <input type="submit" name="back" value="<<Back"><br><hr>
    </form> -->
    <h1>Booking</h1>

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
    <h3>Discount Cupon</h3>
    <form method="post">
      <p>
        if you have the code please type here to get up to 80% discount.
      </p>
      <p>
        <input type="text" name="cupon">
      </p><hr>

      <p>
        <label>Booking Name<label>&nbsp;
        <input type="text" name="name" value="<?= htmlentities($_SESSION['un'])?>" readonly>
      </p>
      <p>
        <label>Booking Address<label>&nbsp;
        <input type="text" name="address">
      </p>
      <p>
        <label>Booking Phone<label>&nbsp;
        <input type="text" name="phone">
      </p>
      <p>
        <label>From<label>&nbsp;
        <input type="date" name="from" placeholder="MM/DD/YYYY">
      </p>
      <p>
        <label>To<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="date" name="to" placeholder="MM/DD/YYYY">
      </p><br>
        <input type="submit" name="book" value="Confirm Bookng"><br><hr>
        <input type="hidden" name="date" value="<?= date("m-d-Y",time())?>" readonly ></p>
    </form>



    <p>
      if you failed to return the vehicle within the given time, dont worry you will be charged as pay as you go service and will be same as the cost per day.
    </p>


  </body>
</html>
