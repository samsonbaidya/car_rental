<?php
require_once "oci.php";
session_start();
if(isset($_POST['mainpage'])){
  header("Location: index.php");
  return;
}
if(isset($_POST['signuppage'])){
  header("Location: signup.php");
  return;
}
if(isset($_SESSION['account'])){
    unset($_SESSION['account']);
}
if(isset($_SESSION['code'])){
    unset($_SESSION['code']);
}
if(isset($_POST['login'])){
  if (isset($_POST['email']) && isset($_POST['pass'])){
    unset($_SESSION['account']);
        if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
              $_SESSION["error"] = "Email and password are required";
              header("Location: login.php");
              return;
        } else {

            $pos = strpos($_POST['email'], '@');
            if($pos == 0){
                $_SESSION["error"] = "Email must have an at-sign (@)";
                header("Location: login.php");
                return;
            }

            try{
              $stmt = $pdo->prepare("SELECT * FROM credentials WHERE email = :email AND password = :password");
              $stmt->execute(array(":email" => $_POST['email'],
                                    ":password" => $_POST['pass'])
                                  );
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              if($row===false){
                error_log("Login fail ".$_POST['email']." $check");
                $_SESSION["error"] = "Incorrect Email or Password";
                header("Location: login.php");
                return;
              }
              if($row['ROLE']== 1){
                $_SESSION["role"] = $row['ROLE'];
                $_SESSION["un"] = $row['USERNAME'];
                $_SESSION["account"] = $_POST["email"];
                $_SESSION["success"] = "Logged in.";
                header("Location: admin-home.php");
                error_log("Login success ".$_POST['email']);
                return;
              }
              else{
                $_SESSION["role"] = $row['ROLE'];
                $_SESSION["un"] = $row['USERNAME'];
                $_SESSION["account"] = $_POST["email"];
                $_SESSION["success"] = "Logged in.";
                header("Location: user-home.php");
                error_log("Login success ".$_POST['email']);
                return;
              }

            }
            catch(Exception $ex){
              echo("Exception message: ". $ex->getMessage());
              header("Location: login.php");
              return;
            }


        }
  }
}


 ?>


 <!DOCTYPE html>
 <html>
 <head>
 <title>Login</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <!-- CSS only -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

 </head>
 <body style="margin:50px;">
 <div class="container">
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
<!-- -------------------card for login-------------------------- -->
        <div class="container d-flex justify-content-center">
        <div class="card bg-light" style="width: 48rem;">
  <!-- <img class="card-img-top" src="car-card.jpg" alt="Card image cap"> -->
  <div class="card-body">
    <h5 class="card-title"><b>LOGIN</b></h5>
    <form method="POST">
     <input class="btn btn-primary" type="submit" name="mainpage" value=" << Main Page "> <span style="margin-right:20px;"></span>
     <input class="btn btn-primary" type="submit" name="signuppage" value=" Signup Page >> "><br>

   </form>
 <h1>Please Log In</h1>
 <?php
     if ( isset($_SESSION["error"]) ) {
         echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
         unset($_SESSION["error"]);
     }
     if ( isset($_SESSION["success"]) ) {
         echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
         unset($_SESSION["success"]);
     }
 ?>
 <form method="POST">
 <label for="nam">Email</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="text" name="email" id="nam"><br/>
 <label for="id_1723">Password</label>
 <input type="password" name="pass" id="id_1723"><br/>
 <input class="btn btn-primary" type="submit" name= "login" value="Log In">

 <a href="#.php">Forgot Password?</a>
 </form>
  </div>
</div>
   <!-- <form method="POST">
     <input type="submit" name="mainpage" value=" << Main Page "> <span style="margin-right:20px;"></span>
     <input type="submit" name="signuppage" value=" Signup Page >> "><br>
   </form>
 <h1>Please Log In</h1>
 <?php
     if ( isset($_SESSION["error"]) ) {
         echo('<p style="color:red">'.$_SESSION["error"]."</p>\n");
         unset($_SESSION["error"]);
     }
     if ( isset($_SESSION["success"]) ) {
         echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
         unset($_SESSION["success"]);
     }
 ?>
 <form method="POST">
 <label for="nam">Email</label>
 <input type="text" name="email" id="nam"><br/>
 <label for="id_1723">Password</label>
 <input type="password" name="pass" id="id_1723"><br/>
 <input type="submit" name= "login" value="Log In">

 <a href="recovery.php">Forgot Password?</a>
 </form> -->
 </div>
 </div>
 </body>
 </html>
