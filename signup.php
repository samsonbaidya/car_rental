<?php
require_once "oci.php";
session_start();
if(isset($_POST['mainpage'])){
  header('Location:index.php');
  return;
}
if(isset($_SESSION['account'])){
    unset($_SESSION['account']);
}


if(isset($_POST['signup'])){
  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['pw']) && isset($_POST['re-pw'])){
    if(strlen($_POST['name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['phone'])<1 || strlen($_POST['pw'])<1 || strlen($_POST['re-pw'])<1){
      $_SESSION["error"] = "All fields are required.";
      header("Location: signup.php");
      return;
    }
    $pos = strpos($_POST['email'], '@');
    if($pos == 0){
      $_SESSION["error"] = "Email must have an at-sign (@)";
      header("Location: signup.php");
      return;
    }
    if(!is_numeric($_POST['phone'])){
      $_SESSION["error"] = "Phone number must be a number.";
      header("Location: signup.php");
      return;
    }
    if($_POST['pw'] != $_POST['re-pw']){
      $_SESSION["error"] = "Password must be matched.";
      header("Location: signup.php");
      return;
    }
    try{
      $stmt = $pdo->prepare("SELECT role FROM credentials WHERE email = :email OR phone = :phone");
      $stmt->execute(array(":email" => $_POST['email'],
                            ":phone" =>  $_POST['phone'])
                          );
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row !== false){
        $_SESSION["error"] = "Account already exist with this email or phone. Login to continue.";
        header("Location: login.php");
        return;
      }
    }
    catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
      header("Location: signup.php");
      return;
    }

    try{
      // $password =$_POST['pw'];
      // $sql = "INSERT INTO CREDENTIALS (USERNAME, EMAIL, PASSWORD, PHONE, ROLE)
      //                     VALUES ( :name, :email, :pass, :phone, 0)";
      // $stmt = $pdo->prepare($sql);
      // $stmt->execute(array(
      //     ':name' => $_POST['name'],
      //     ':email' => $_POST['email'],
      //     ':pass' => $password,
      //     ':phone' => $_POST['phone'])
      //   );


          $sql = "CREATE OR REPLACE PROCEDURE INSERT_USER
                  IS
                  BEGIN
                      INSERT INTO CREDENTIALS VALUES ('".$_POST['name']."','".$_POST['email']."','".$_POST['pw']."','".$_POST['phone']."',0);
                  END;";
          execute($sql);
          $sql  = "begin INSERT_USER; end;";
          execute($sql);

          $_SESSION["success"] = "Signup Successfully.";
          unset($_SESSION['code']);
          header("Location: index.php");
          return;
    }catch(Exception $ex){
      echo("Exception message: ". $ex->getMessage());
        $_SESSION["error"] = " LOL123.";
      header("Location: signup.php");
      return;
    }

  }else{
    $_SESSION['error'] = "Fillup all the field correctly.";
    header('Location: signup.php');
    return;
  }


}


 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>signup</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <!-- CSS only -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </head>
  <body style="margin:50px;">
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

<!-- -------------------card for sign up------------------------------ -->
        <div class="container d-flex justify-content-center">
        <div class="card" style="width: 48rem;">
  <!-- <img class="card-img-top" src="car-card.jpg" alt="Card image cap"> -->
  <div class="card-body">
    <h5 class="card-title"><b>Sign Up</b></h5>
    <form method="POST">

<input class="btn btn-primary" type="submit" name="mainpage" value="<< Main Page"><br>

</form>
<!-- <h1> Sign UP</h1> -->

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
<table>
  <tr>
    <td>Name</td>
    <td> <input type="text" name="name" > </td>
  </tr>
  <tr>
  <td>Email</td>
    <td> <input type="text" name="email" > </td>
  </tr>
  <tr>
    <td>Phone</td>
      <td> <input type="text" name="phone"> </td>
    </tr>
  <tr>
  <tr>
    <td>Password</td>
      <td> <input type="password" name="pw"> </td>
    </tr>
  <tr>
  <tr>
    <td>Re-type Password</td>
      <td> <input type="password" name="re-pw"> </td>
    </tr>
  <tr>
    <td> <input class="btn btn-primary" type="submit" name="signup" value='Signup'> </td>
  </tr>
</table>
</form>

<p><a href="login.php">Login</a> Or Goto <a href="index.php"> Main Page </a> </p>
  </div>
</div>
      <!-- <form method="POST">

        <input type="submit" name="mainpage" value="<< Main Page"><br>

      </form>
      <h1> Sign UP</h1>

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
        <table>
          <tr>
            <td>Name</td>
            <td> <input type="text" name="name" size=60> </td>
          </tr>
          <tr>
          <td>Email</td>
            <td> <input type="text" name="email" size=60> </td>
          </tr>
          <tr>
            <td>Phone</td>
              <td> <input type="text" name="phone"> </td>
            </tr>
          <tr>
          <tr>
            <td>Password</td>
              <td> <input type="password" name="pw"> </td>
            </tr>
          <tr>
          <tr>
            <td>Re-type Password</td>
              <td> <input type="password" name="re-pw"> </td>
            </tr>
          <tr>
            <td> <input type="submit" name="signup" value='Signup'> </td>
          </tr>
        </table>
      </form>

      <p><a href="login.php">Login</a> Or Goto <a href="index.php"> Main Page </a> </p> -->
     </div>
  </body>
</html>
