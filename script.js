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


const apiKey ='5a2470f05b868577fa1e70d5870fd00d'

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







async function updateWeatherInfo(city){
    const weatherData = await getFetchData('weather',city )
    if(weatherData.cod != 200){
        showDisplaySection(notFoundSection)
        return
    }

    console.log(weatherData)

    const{
        
        name: country,
        main: { temp, humidity },
        weather: [{ id, main}],
        wind : { speed }

    } = weatherData

    countryTxt.textContent = country
    tempTxt.textContent = Math.round(temp) + ' °C'
    conditionTxt.textContent = main
    humidityTxt.textContent = humidity + ' %'
    windTxt.textContent = speed + ' m/s'   

    weatherSummaryIcon.src = `assets-1/assets/weather/${getWeatherIcon(id)}` 
     


    showDisplaySection(weatherInfoSection)
}

function showDisplaySection(section){
    [weatherInfoSection, searchCitySection, notFoundSection]
    .forEach(s => s.style.display = 'none')

    section.style.display = 'flex'

}