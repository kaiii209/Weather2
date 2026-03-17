const cityInput = document.querySelector('.city-input')
const searchBtn = document.querySelector('.search-btn')

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
function getFetchData(){

}
function updateWeatherInfo(city){
    const weatherData = getFetchData()
}