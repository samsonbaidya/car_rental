<?php
require_once "oci.php";
session_start();
if(!isset($_SESSION['account'])){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if(!isset($_GET['car_id'])){
  $_SESSION['error'] = "Car id not found.";
  header('Location: user-home.php');
  return;
}


$sql = "UPDATE CAR SET AVAILABILITY = 'yes' WHERE CAR_ID = :car_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':car_id' =>$_REQUEST['car_id']));

$stmt = $pdo->prepare("SELECT * FROM CUSTOMER WHERE CUSTOMER_NAME = :customer_name ");
$stmt->execute(array(":customer_name" =>  $_SESSION['un']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "SELECT * FROM CARBOOK WHERE CAR_ID = :car_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':car_id' => $_GET['car_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if($row === false){
  $_SESSION['error'] = "No booking found.";
  header('Location: user-home.php');
  return;
}



$sql = "DELETE FROM BILL WHERE BOOKING_ID = :booking_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':booking_id' => $rows['BOOKING_ID']));

$sql = "DELETE FROM DISCOUNT WHERE BOOKING_ID = :booking_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':booking_id' => $rows['BOOKING_ID']));


$sql = "DELETE FROM CUSTOMER WHERE BOOKING_ID = :booking_id OR CUSTOMER_NAME = :c_name";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':booking_id' => $rows['BOOKING_ID'],
                    ':c_name' => $_SESSION['un']));

$sql = "DELETE FROM CARBOOK WHERE BOOKING_ID = :booking_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':booking_id' => $rows['BOOKING_ID']));


$sql = "DELETE FROM BOOKING WHERE BOOKING_ID = :booking_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':booking_id' => $rows['BOOKING_ID']));

$_SESSION['success'] = 'car returned.';
header( 'Location: user-home.php');
return;
 ?>
