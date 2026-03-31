const cityInput = document.querySelector('.city-input')
const searchBtn = document.querySelector('.search-btn')

const weatherInfoSection = document.querySelector('.weather-info')
const notFoundSection= document.querySelector('.not-found')
const searchCitySection = document.querySelector('.search-city')    

const countryTxt = document.querySelector('.country-text')
const tempTxt = document.querySelector('.temp-txt')
const conditionTxt = document.querySelector('.condition-txt')
const humidityTxt = document.querySelector('.humidity-value-txt')
const windTxt = document.querySelector('.wind-txt')
const weatherSummaryIcon = document.querySelector('.weather-summary-img')
const currentDateTxt = document.querySelector('.current-date-text')
const forecastItemsContainer = document.querySelector('.forecast-items-container')
const addFavoriteBtn = document.querySelector('#addFavorite')


const apiKey ='5a2470f05b868577fa1e70d5870fd00d'
// Location variables
let userLocationCity = ''
let userLocationTemp = ''
let userLocationCondition = ''

const locationBanner = document.querySelector('#locationBanner')
const sideBySide = document.querySelector('#sideBySide')
const locCity = document.querySelector('#locCity')
const locTemp = document.querySelector('#locTemp')
const locCondition = document.querySelector('#locCondition')
const sideLocCity = document.querySelector('#sideLocCity')
const sideLocTemp = document.querySelector('#sideLocTemp')
const sideLocCondition = document.querySelector('#sideLocCondition')
const sideSearchCity = document.querySelector('#sideSearchCity')
const sideSearchTemp = document.querySelector('#sideSearchTemp')
const sideSearchCondition = document.querySelector('#sideSearchCondition')

// Detect user location on page load
async function detectUserLocation() {
    if (!navigator.geolocation) return

    navigator.geolocation.getCurrentPosition(async (position) => {
        const { latitude, longitude } = position.coords

        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`
        )
        const data = await response.json()

        if (data.cod !== 200) return

        userLocationCity = data.name
        userLocationTemp = Math.round(data.main.temp) + '°C'
        userLocationCondition = data.weather[0].main

        // Update banner
        locCity.textContent = userLocationCity + ', ' + data.sys.country
        locTemp.textContent = userLocationTemp
        locCondition.textContent = userLocationCondition

        locationBanner.style.display = 'flex'

    }, (error) => {
        console.log('Geolocation error:', error)
    })
}

detectUserLocation()

let currentCity =''

searchBtn.addEventListener('click',() =>{
    if(cityInput.value.trim() != ''){
        updateWeatherInfo(cityInput.value)
        cityInput.value =''
        cityInput.blur()

    }
})
cityInput.addEventListener('keydown',(event) => {
    if(event.key == 'Enter' && cityInput.value.trim() != ''){
        updateWeatherInfo(cityInput.value)
        cityInput.value =''
        cityInput.blur()

    }
})
 async function getFetchData(endPoint,city){
    const apiUrl = `https://api.openweathermap.org/data/2.5/${endPoint}?q=${city}&appid=${apiKey}&units=metric`

    const response =  await fetch(apiUrl)
    return response.json()

}

function getWeatherIcon(id){

    if (id <= 232) return 'thunderstorm.svg'
    if (id <= 321) return 'drizzle.svg'
    if (id <= 531) return 'rain.svg'
    if (id <= 622) return 'snow.svg'
    if (id <= 781) return 'atmosphere.svg'
    if (id === 800) return 'clear.svg'
    else return 'clouds.svg'
}

function getCurrentDate(){
    const currentDate = new Date()
    const options ={
        weekday : 'short',
        day : '2-digit',
        month : 'short'
    }
   return currentDate.toLocaleDateString('en-GB', options)

}





async function updateWeatherInfo(city){
    const weatherData = await getFetchData('weather',city )
    if(weatherData.cod != 200){
        showDisplaySection(notFoundSection)
        return
    }

   

    const{
        
        name: country,
        main: { temp, humidity },
        weather: [{ id, main}],
        wind : { speed }

    } = weatherData

    countryTxt.textContent = country
    currentCity = country
    tempTxt.textContent = Math.round(temp) + ' °C'
    conditionTxt.textContent = main
    humidityTxt.textContent = humidity + ' %'
    windTxt.textContent = speed + ' m/s'   
    currentDateTxt.textContent = getCurrentDate()
    weatherSummaryIcon.src = `assets-1/assets/weather/${getWeatherIcon(id)}` 

    await updateForecastInfo(city) 


    showDisplaySection(weatherInfoSection)
}

 async function updateForecastInfo(city){
    const forecastsData = await getFetchData('forecast', city)
    const timeTaken = '12:00:00'
    const todayDate = new Date().toISOString().split('T')[0]

    forecastItemsContainer.innerHTML =''


    forecastsData.list.forEach(forecastWeather =>{
        if(forecastWeather.dt_txt.includes(timeTaken) && !forecastWeather.dt_txt.includes(todayDate))
       updateForecastItems(forecastWeather)
    } )
   
    
}

function updateForecastItems(weatherData){
    console.log(weatherData)
    const {
        dt_txt : date,
        weather : [{id}],
        main : { temp }
    } = weatherData

    const dateTaken = new Date(date)
    const dateOptions ={
        day : '2-digit',
        month : 'short'
    }

    const dateResult = dateTaken.toLocaleDateString('en-US', dateOptions)

const forecastItem = `
   <div class="forecast-item">
                  <h5 class="forecast-item-date regular-txt">${dateResult}</h5>
                  <img src="assets-1/assets/weather/${getWeatherIcon(id)}" class="forecast-item-img">
                  <h5 class="forecast-item-temp">${Math.round(temp)}°C</h5>
            </div>
  `
 forecastItemsContainer.insertAdjacentHTML('beforeend', forecastItem)


}

  
  

function showDisplaySection(section) {
    [weatherInfoSection, searchCitySection, notFoundSection]
        .forEach(s => s.style.display = 'none')

    section.style.display = 'flex'

    // If weather is showing AND we have user location, switch to side-by-side
    if (section === weatherInfoSection && userLocationCity !== '') {
        locationBanner.style.display = 'none'
        sideBySide.style.display = 'flex'

        // Fill side-by-side location card
        sideLocCity.textContent = userLocationCity
        sideLocTemp.textContent = userLocationTemp
        sideLocCondition.textContent = userLocationCondition

        // Fill side-by-side searched card
        sideSearchCity.textContent = currentCity
        sideSearchTemp.textContent = tempTxt.textContent
        sideSearchCondition.textContent = conditionTxt.textContent
    } else if (section !== weatherInfoSection) {
        // If going back to search/not-found, show banner again
        sideBySide.style.display = 'none'
        if (userLocationCity !== '') locationBanner.style.display = 'flex'
    }
}
// btnjs
// btnjs
if(addFavoriteBtn){
    addFavoriteBtn.addEventListener("click", () => {

        if(currentCity === ''){
            alert("Search a city first")
            return
        }

        fetch("addfav.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "city=" + currentCity
        })
        .then(res => res.text())
        .then (data => {
            alert(data)
            loadFavorites()
        })
        .catch(err => console.log(err))

    })
}

const favoriteList = document.querySelector("#favoriteList")

function loadFavorites(){
    fetch("get_favorites.php")
    .then(res => res.json())
    .then(data => {

        favoriteList.innerHTML = ""

        data.forEach(city => {

            const li = document.createElement("li")
            li.textContent = city

            li.addEventListener("click", () =>{
                updateWeatherInfo(city)
            })

            favoriteList.appendChild(li)

        })

    })
}

loadFavorites()
async function detectUserLocation() {
    if (!navigator.geolocation) return

    navigator.geolocation.getCurrentPosition(async (position) => {
        const { latitude, longitude } = position.coords

        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${apiKey}&units=metric`
        )
        const data = await response.json()

        if (data.cod !== 200) return

        userLocationCity = data.name
        userLocationTemp = Math.round(data.main.temp) + '°C'
        userLocationCondition = data.weather[0].main

        // Update banner
        locCity.textContent = userLocationCity + ', ' + data.sys.country
        locTemp.textContent = userLocationTemp
        locCondition.textContent = userLocationCondition

        locationBanner.style.display = 'flex'

    }, (error) => {
        console.log('Geolocation error:', error)
    })
}

detectUserLocation()