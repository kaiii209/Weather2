<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.html");
}
include "php/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Cities</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="fav.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <main class="main-container">
        <div class="fav-page-container">

            <!-- Back Button -->
            <a href="index.php" class="back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back
            </a>

            <h2 class="fav-page-title">
                <span class="material-symbols-outlined">star</span>
                Favorite Cities
            </h2>

            <ul class="fav-page-list" id="favPageList">
                <li class="fav-loading">Loading...</li>
            </ul>

        </div>
    </main>

    <script>
const apiKey = '5a2470f05b868577fa1e70d5870fd00d'
const favPageList = document.querySelector('#favPageList')

async function loadFavorites(){
    const res = await fetch('get_favorites.php')
    const cities = await res.json()

    favPageList.innerHTML = ''

    if(cities.length === 0){
        favPageList.innerHTML = '<li class="fav-empty">No favorite cities yet!</li>'
        return
    }

    for(const city of cities){
        const weatherRes = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`
        )
        const weatherData = await weatherRes.json()
        const temp = weatherData.cod === 200 ? Math.round(weatherData.main.temp) + '°C' : '--'
        const condition = weatherData.cod === 200 ? weatherData.weather[0].main : '--'
        const humidity = weatherData.cod === 200 ? weatherData.main.humidity + '%' : '--'
        const wind = weatherData.cod === 200 ? weatherData.wind.speed + ' m/s' : '--'
        const icon = weatherData.cod === 200 ? getWeatherIcon(weatherData.weather[0].id) : 'clouds.svg'

        const li = document.createElement('li')
        li.className = 'fav-page-item'
        li.innerHTML = `
            <div class="fav-item-main">
                <div class="fav-item-left">
                    <img src="assets-1/assets/weather/${icon}" class="fav-item-icon">
                    <div class="fav-item-info">
                        <h3 class="fav-item-city">📍 ${city}</h3>
                        <p class="fav-item-condition">${condition}</p>
                        <div class="fav-item-details">
                            <span>💧 ${humidity}</span>
                            <span>🌬️ ${wind}</span>
                        </div>
                    </div>
                </div>
                <div class="fav-item-right">
                    <h2 class="fav-item-temp">${temp}</h2>
                    <button class="fav-remove-btn" onclick="removeFavorite('${city}', this)">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
            </div>

            <!-- Accordion Forecast -->
            <div class="fav-accordion" style="display:none;">
                <div class="fav-forecast-container" id="forecast-${city.replace(/\s/g,'_')}">
                    <p class="fav-loading">Loading forecast...</p>
                </div>
            </div>
        `
        favPageList.appendChild(li)

        // Click to expand
        li.querySelector('.fav-item-main').style.cursor = 'pointer'
        li.querySelector('.fav-item-main').addEventListener('click', (e) => {
            if(e.target.closest('.fav-remove-btn')) return
            const accordion = li.querySelector('.fav-accordion')
            const isOpen = accordion.style.display === 'block'
            // Close all others
            document.querySelectorAll('.fav-accordion').forEach(a => a.style.display = 'none')
            document.querySelectorAll('.fav-page-item').forEach(i => i.classList.remove('active'))
            if(!isOpen){
                accordion.style.display = 'block'
                li.classList.add('active')
                loadForecast(city)
            }
        })
    }
}

async function loadForecast(city){
    const forecastId = 'forecast-' + city.replace(/\s/g,'_')
    const container = document.querySelector('#' + forecastId)
    if(container.dataset.loaded) return
    container.dataset.loaded = true

    const res = await fetch(
        `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric`
    )
    const data = await res.json()
    const timeTaken = '12:00:00'
    const todayDate = new Date().toISOString().split('T')[0]

    container.innerHTML = ''

    data.list.forEach(item => {
        if(item.dt_txt.includes(timeTaken) && !item.dt_txt.includes(todayDate)){
            const date = new Date(item.dt_txt).toLocaleDateString('en-US', { day:'2-digit', month:'short' })
            const icon = getWeatherIcon(item.weather[0].id)
            const temp = Math.round(item.main.temp) + '°C'
            container.innerHTML += `
                <div class="fav-forecast-item">
                    <p class="fav-forecast-date">${date}</p>
                    <img src="assets-1/assets/weather/${icon}" class="fav-forecast-icon">
                    <p class="fav-forecast-temp">${temp}</p>
                </div>
            `
        }
    })
}

function getWeatherIcon(id){
    if(id <= 232) return 'thunderstorm.svg'
    if(id <= 321) return 'drizzle.svg'
    if(id <= 531) return 'rain.svg'
    if(id <= 622) return 'snow.svg'
    if(id <= 781) return 'atmosphere.svg'
    if(id === 800) return 'clear.svg'
    else return 'clouds.svg'
}

async function removeFavorite(city, btn){
    const res = await fetch('php/remove_fav.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'city=' + city
    })
    const data = await res.text()
    if(data === 'removed'){
        btn.closest('li').remove()
        if(favPageList.children.length === 0){
            favPageList.innerHTML = '<li class="fav-empty">No favorite cities yet!</li>'
        }
    }
}

loadFavorites()
</script>
</body>
</html>