<?php
require_once "oci.php";
require_once "conn.php";

session_start();
if(!isset($_SESSION['account']) || $_SESSION['role'] != 1){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}
if ( isset($_POST['home']) ) {
    header('Location: admin-home.php');
    return;
}
if (isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}


if(isset($_POST['Update'])){

  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone'])){
    if(strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['phone']) < 1){
      $_SESSION["error"] = "All field required";
      header("Location: settings-admin.php");
      return;
    }
    $pos = strpos($_POST['email'], '@');
    if($pos == 0){
      $_SESSION["error"] = "Email must have an at-sign (@)";
      header("Location: settings-admin.php");
      return;
    }
    if(!is_numeric($_POST['phone'])){
      $_SESSION["error"] ="Phone number must be a number";
      header("Location: settings-admin.php");
      return;
    }

    try {
      $stmt = $pdo->prepare("SELECT * FROM CREDENTIALS WHERE EMAIL = :email  AND USERNAME <> :un OR PHONE = :phone AND USERNAME <> :un");
      $stmt->execute(array(':un' =>  $_SESSION['un'],
                            ':email' => $_POST['email'],
                            ':phone' => $_POST['phone']));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row !== false){
        $_SESSION["error"] = "Email and Phone must be unique";
        header("Location: settings-admin.php");
        return;
      }
    } catch (Exception $eX) {
      echo("Exception message: ". $ex->getMessage());
      header("Location: settings-admin.php");
      return;
    }


    try{
      // $sql = "UPDATE CREDENTIALS SET USERNAME = :name ,
      //         EMAIL = :email , PHONE = :phone
      //         WHERE USERNAME = :un";
      // $stmt = $pdo->prepare($sql);
      // $stmt->execute(array(
      //     ':name' => $_POST['name'],
      //     ':email' => $_POST['email'],
      //     ':phone' => $_POST['phone'],
      //     ':un' => $_SESSION['un']
      //
      //   ));


      $sql = "CREATE OR REPLACE PROCEDURE UPDATE_USER
              IS
              BEGIN
                  UPDATE CREDENTIALS SET USERNAME = '".$_POST['name']."', EMAIL = '".$_POST['email']."', PHONE = '".$_POST['phone']."' WHERE USERNAME = '".$_POST['un']."';
              END;";
      execute($sql);
      $sql  = "begin UPDATE_USER; end;";
      execute($sql);

      $_SESSION['success'] = 'Account Information updated';
      $_SESSION['account'] = $_POST['email'];
      $_SESSION['un'] = $_POST['name'];
      header("Location: admin-home.php");
      return;
    }catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
      $_SESSION['error'] = 'lol 12';

      header("Location: settings-admin.php");
      return;
    }

    }else{
    $_SESSION['error'] = "All fields are Required.";
    header("Location: settings-admin.php");
    return;
    }

}



try{
  $stmt = $pdo->prepare("SELECT * FROM CREDENTIALS WHERE EMAIL = :email");
  $stmt->execute(array(":email" =>  $_SESSION['account']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if($row === false){
    $_SESSION["error"] = "Account Not Found.";
    header("Location: admin-home.php");
    return;
  }
  $n = htmlentities($row['USERNAME']);
  $e = htmlentities($row['EMAIL']);
  $ph = htmlentities($row['PHONE']);

}catch(Exception $ex){
  echo("Exception message: ". $ex->getMessage());
  header("Location: settings-admin.php");
  return;
}


 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>settings</title>
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

<h1>Account Settings </h1>


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
  <p>Name: <?=htmlentities($n)?> &rarr;
  <input type="text" name="name"></p>
  <p>Email: <?=htmlentities($e)?> &rarr;
  <input type="text" name="email"></p>
  <p>Phone: <?=htmlentities($ph)?> &rarr;
  <input type="text" name="phone"></p>

  <input type="hidden" name="un" value="<?= htmlentities($n) ?>"></p>

  <input type="submit" name="Update" value="Update">
  </form>

</body>
</html>
