<?php
session_start();
include "php/db.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT city FROM favorites WHERE user_id='$user_id'";
$result = mysqli_query($conn,$sql);

$favorites = [];

while($row = mysqli_fetch_assoc($result)){
    $favorites[] = $row['city'];
}

echo json_encode($favorites);
?>