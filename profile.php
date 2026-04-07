<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.html");
}
include "php/db.php";

// Fetch user data
$email = $_SESSION['user'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Fetch total favorites
$fav_sql = "SELECT COUNT(*) as total FROM favorites WHERE user_id='" . $_SESSION['user_id'] . "'";
$fav_result = mysqli_query($conn, $fav_sql);
$fav_row = mysqli_fetch_assoc($fav_result);
$total_favorites = $fav_row['total'];

// Get initials from username
$words = explode(' ', $user['username']);
$initials = strtoupper(substr($words[0], 0, 1));
if(isset($words[1])) $initials .= strtoupper(substr($words[1], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="profile-container">

        <!-- Back Button -->
        <a href="index.php" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
        </a>

        <!-- Avatar -->
        <div class="avatar-circle">
            <?php echo $initials; ?>
        </div>

        <!-- Username & Email -->
        <h2 class="profile-username"><?php echo $user['username']; ?></h2>
        <p class="profile-email"><?php echo $user['email']; ?></p>

        <div class="profile-divider"></div>

        <!-- Stats Cards -->
        <div class="profile-cards">
            <div class="profile-card">
                <span class="material-symbols-outlined">star</span>
                <h3><?php echo $total_favorites; ?></h3>
                <p>Favorites</p>
            </div>
            <div class="profile-card">
                <span class="material-symbols-outlined">location_on</span>
                <h3 id="defaultCity">--</h3>
                <p>Your Location</p>
            </div>
        </div>

        <div class="profile-divider"></div>

        <!-- Logout -->
        <a href="php/logout.php" class="logout-btn">
            <span class="material-symbols-outlined">logout</span>
            Logout
        </a>

    </div>

    <script>
        // Get user location city
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(async (pos) => {
                const { latitude, longitude } = pos.coords
                const apiKey = '5a2470f05b868577fa1e70d5870fd00d'
                const res = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`)
                const data = await res.json()
                document.querySelector('#defaultCity').textContent = data.name + ', ' + data.sys.country
            })
        }
    </script>
</body>
</html>