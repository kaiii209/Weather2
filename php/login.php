<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) > 0){

$row = mysqli_fetch_assoc($result);

if(password_verify($password,$row['password'])){

$_SESSION['user_id'] = $row['id'];
$_SESSION['user'] =$row['email'];
$_SESSION['username'] = $row['username'];

header("Location: ../index.php");

}else{
echo "Wrong Password";
}

}else{
echo "User not found";
}
?>