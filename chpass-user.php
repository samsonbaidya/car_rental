<?php
require_once "oci.php";
session_start();
if(!isset($_SESSION['account']) || $_SESSION['role'] != 0){
  $_SESSION['error'] = "Login First.";
  header('Location: login.php');
  return;
}

if ( isset($_POST['logout']) ) {
    header('Location: logout.php');
    return;
}
if ( isset($_POST['home']) ) {
    header('Location: user-home.php');
    return;
}


if(isset($_POST['Update'])){

  if(isset($_POST['old-pw'])  && isset($_POST['new-pw'])  && isset($_POST['re-pw'])){
    if(strlen($_POST['old-pw']) < 1 || strlen($_POST['new-pw']) < 1 || strlen($_POST['re-pw']) < 1){
      $_SESSION["error"] = "All field required";
      header("Location: chpass-user.php");
      return;
    }
    if(strlen($_POST['new-pw']) < 8 || strlen($_POST['re-pw']) < 8){
      $_SESSION["error"] = "Pass must be 8 character long.";
      header("Location: chpass-user.php");
      return;
    }
    if(is_numeric($_POST['new-pw']) || is_numeric($_POST['re-pw'])){
      $_SESSION["error"] ="Password must contain a letter and a number.";
      header("Location: chpass-user.php");
      return;
    }
    if($_POST['new-pw'] != $_POST['re-pw']){
      $_SESSION["error"] = "Password must be matched.";
      header("Location: chpass-user.php");
      return;
    }

    $check =$_POST['old-pw'];   //salted-hash protected pattern used
    try{
      $stmt = $pdo->prepare("SELECT * FROM CREDENTIALS WHERE EMAIL = :email AND PASSWORD = :password");
      $stmt->execute(array(":email" => $_SESSION['account'],
                            ":password" => $check)
                          );
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row===false){
        $_SESSION["error"] = "Incorrect Password";
        header("Location: chpass-user.php");
        return;
      }
    }
    catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
      header("Location: chpass-user.php");
      return;
    }

    $password = $_POST['new-pw'];
    try{
      $sql = "UPDATE USER SET PASSWORD = :password
                      WHERE EMAIL = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(
          ':password' => $password,
          ':email' => $_SESSION['account']
                        ));
      $_SESSION['success'] = 'Password Changed Successfully.';
      header( 'Location: user-home.php');
      return;
    }catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
      header("Location: chpass-user.php");
      return;
    }

  }else{
    $_SESSION['error'] = "All fields are Required.";
    header("Location: chpass-user.php");
    return;
    }

}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>change password User</title>
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
  <input type="submit" name="logout" value="Logout"><br><hr>
    <input type="submit" name="home" value=" << Home ">
</form> -->

<h1>Change Password</h1>


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
  <p>Old Password:
  <input type="password" name="old-pw" ></p>
  <p>New Password:
  <input type="password" name="new-pw" ></p>
  <p>Confirm Password:
  <input type="password" name="re-pw" ></p>

  <input type="submit" name="Update" value="Change">
  </form>

</body>
</html>
