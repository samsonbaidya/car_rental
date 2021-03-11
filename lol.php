<?php
require_once "conn.php";
session_start();

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


//
// $sql = "CREATE OR REPLACE PROCEDURE INSERT_USER
//         IS
//         BEGIN
//             insert into CREDENTIALS VALUES ('".$_POST['username']."','".$_POST['usertype']."','".$_POST['firstname']."','".$_POST['lastname']."',CURRENT_TIMESTAMP);
//         END;";






if(isset($_POST['submit']))
{
    $sql = "CREATE OR REPLACE PROCEDURE insertUser
            IS
            BEGIN
                insert into users values ('".$_POST['username']."','".$_POST['usertype']."','".$_POST['firstname']."','".$_POST['lastname']."',CURRENT_TIMESTAMP);
                  DBMS_OUTPUT.PUT_LINE('DONE DONE DONE ');
            END;";

  $S= get($sql);
echo($s);

    $sql  = "begin insertUser; end;";

    $S= execute($sql);


}


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

    <form action="" method="post">
        <h3>Add Admin/ShopKeeper</h3>
        <table>
            <tr>
                <th>Username</th>
                <td><input type="text" name="username" id="" placeholder="Enter Username"></td>
            </tr>
            <tr>
                <th>Firstname</th>
                <td><input type="text" name="firstname" id="" placeholder="Enter Firstname"></td>
            </tr>
            <tr>
                <th>Lastname</th>
                <td><input type="text" name="lastname" id="" placeholder="Enter Lastname"></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" id="" placeholder="Enter Password"></td>
            </tr>
            <tr>
                <th>Date of birth</th>
                <td><input type="date" name="dateofbirth" id=""></td>
            </tr>
            <tr>
                <th>Usertype</th>
                <td>
                    <select name="usertype" id="cars">
                        <option value="Admin">Admin</option>
                        <option value="Shopkeeper">ShopKeeper</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Register" name="submit"></td>
            </tr>

        </table>
    </form>
  </body>
</html>
