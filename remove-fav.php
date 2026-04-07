<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];
$city = $_POST['city'];

$sql = "DELETE FROM favorites WHERE user_id='$user_id' AND city='$city'";

if(mysqli_query($conn, $sql)){
    echo "removed";
} else {
    echo "error";
}
?>