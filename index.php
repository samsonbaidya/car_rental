<?php
require_once "oci.php";
session_start();
if(isset($_SESSION['account'])){
    unset($_SESSION['account']);
}
if(isset($_SESSION['code'])){
    unset($_SESSION['code']);
}
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Payroll System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet" />
    <!-- CSS only -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  </head>

  <body style="margin:10px;margin-top:-50px">
    <div class="container">
      <!-- <h1>Car Rental System -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="#">CAR RENTAL SYSTEM </a>
        <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="signup.php">Sign Up</a>
            <a class="nav-item nav-link" href="conf-signup.php">Sign Up as Admin</a>
            </div>
        </div>
        </nav>

      </h1>
      <!-- <h2>
        <span style="margin-left: 20px;"></span> <a href="login.php">Log In </a>
        <span style="margin-left: 20px;"></span> <?php //echo "|"; ?>
        <span style="margin-left: 20px;"></span> <a href="signup.php"> Sign up </a>
        <span style="margin-left: 20px;"></span> <?php //echo "|"; ?>
        <span style="margin-left: 20px;"></span> <a href="conf-signup.php"> Sign up as admin</a>
      </h2> -->

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




      <!-- <h4>Rules for Signup</h4>
      <ul>
        <li>
          <p>You must know the <span style="font-weight: bold;"> Reference number</span> to Signup. Contact
          <span style="font-weight: bold;"> HR Members </span> if you don't. </p>
        </li>
        <li>
            <p>Signup option is only for <span style="font-weight: bold;"> HR Members.</span> Don't Signup if you are not HR Member. </p>
        </li>
      </ul> -->


    </div>
     <div class="slider">
     <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner"style="height:80vh;"style="background-size:cover;">
    <div class="carousel-item active" >
      <img class="d-block w-100" src="car1.jpg" alt="First slide">
    </div>
    <div class="carousel-item" style="backhround-size=cover">
      <img class="d-block w-100" src="car2.jpg" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="car3.jpg" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

    </div>

    <div class="container d-flex justify-content-center">
    <div class="card" style="width: 18rem;">
  <img class="card-img-top" src="car-card.jpg" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><b>Rules for Sign Up</b></h5>
    <p class="card-text"><ul>
        <li>
          <p>You must know the <span style="font-weight: bold;"> Reference number</span> to Signup. Contact
          <span style="font-weight: bold;"> HR Members </span> if you don't. </p>
        </li>
        <li>
            <p>Signup option is only for <span style="font-weight: bold;"> HR Members.</span> Don't Signup if you are not HR Member. </p>
        </li>
      </ul></p>
    <a href="signup.php" class="btn btn-primary">SIGN UP</a>
  </div>
</div>
<!-- --------------card-2---------------------- -->
<div class="card" style="width: 18rem;">
  <img class="card-img-top" src="car-card.jpg" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><b>LOGIN</b></h5>
    <p class="card-text">LOGIN in our system and experience our great deal.</p>
    <a href="login.php" class="btn btn-primary">LOGIN</a>
  </div>
</div>
<!-- ---------------------card-3---------------------- -->
<div class="card" style="width: 18rem;">
  <img class="card-img-top" src="car-card.jpg" alt="Card image cap">
  <div class="card-body">
    <h5 class="card-title"><b>Sign Up</b></h5>
    <p class="card-text">Are you our HR member? then, Sign Up right now!!</p>
    <h7><b>Rules for Sign Up</b></h7>
    <p class="card-text"><ul>
        <li>
          <p>You must know the <span style="font-weight: bold;"> Reference number</span> to Signup. Contact
          <span style="font-weight: bold;"> HR Members </span> if you don't. </p>
        </li>
        <li>
            <p>Signup option is only for <span style="font-weight: bold;"> HR Members.</span> Don't Signup if you are not HR Member. </p>
        </li>
      </ul></p>
    <a href="conf-signup.php" class="btn btn-primary">SIGN UP as ADMIN</a>


  </div>
</div>
    </div>

    <footer style="color:black;" class="panel-footer">
      <div class="container">
        <div class="text-center">&copy; Copyright Pooling System BD 2020</div>
      </div>
    </footer>




  </body>
</html>
