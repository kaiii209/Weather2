<?php
session_start();

$conn = new mysqli("localhost","root","","weather_app");

if($conn->connect_error){
    die("Connection failed");
}

$user_id = $_SESSION['user_id'];
$city = $_POST['city'];

$sql = "INSERT INTO favorites (user_id, city) VALUES ('$user_id','$city')";

if($conn->query($sql)){
    echo "City added to favorites!";
}else{
    echo "City already in favorites!";
}
?>