<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <main class="main-container">

        <!-- Back Button -->
        <a href="index.php" class="back-btn">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
        </a>

        <!-- App Logo & Name -->
        <div class="about-header">
            <div class="about-logo">
                <span class="material-symbols-outlined">cloud</span>
            </div>
            <h2 class="about-app-name">Weather App</h2>
            <p class="about-version">Version 1.0.0</p>
        </div>

        <div class="about-divider"></div>

        <!-- Description -->
        <p class="about-description">
            A modern weather application that provides real-time weather information for any city in the world. Stay updated with live forecasts, manage your favourite cities, and enjoy a beautiful glassmorphism UI.
        </p>

        <div class="about-divider"></div>

        <!-- Features -->
        <div class="about-section">
            <h4 class="about-section-title">
                <span class="material-symbols-outlined">star</span>
                Features
            </h4>
            <div class="about-features">
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>Auto location detection</span>
                </div>
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">cloud</span>
                    <span>Real-time weather data</span>
                </div>
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span>5-day forecast</span>
                </div>
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">favorite</span>
                    <span>Favourite cities</span>
                </div>
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">palette</span>
                    <span>Dynamic backgrounds</span>
                </div>
                <div class="about-feature-item">
                    <span class="material-symbols-outlined">lock</span>
                    <span>Secure login system</span>
                </div>
            </div>
        </div>

        <div class="about-divider"></div>

        <!-- Tech Stack -->
        <div class="about-section">
            <h4 class="about-section-title">
                <span class="material-symbols-outlined">code</span>
                Built With
            </h4>
            <div class="about-tech-stack">
                <span class="tech-badge">HTML</span>
                <span class="tech-badge">CSS</span>
                <span class="tech-badge">JavaScript</span>
                <span class="tech-badge">PHP</span>
                <span class="tech-badge">MySQL</span>
                <span class="tech-badge">OpenWeatherMap API</span>
            </div>
        </div>

        <div class="about-divider"></div>

        <!-- Developer -->
        <div class="about-dev">
            <span class="material-symbols-outlined">person</span>
            <div>
                <p class="about-dev-label">Developed by</p>
                <p class="about-dev-name"><?php echo $_SESSION['username'] ?? 'BCA Student'; ?></p>
            </div>
        </div>

        <p class="about-footer">BCA Project &copy; 2025-2026</p>

    </main>
</body>
</html>