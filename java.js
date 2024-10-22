//pendiente a ajustes segun mediciones del sensor

var cnt = document.getElementById("count");
var sphereInner = document.getElementById("sphere-inner");
var temperatureDisplay = document.getElementById("temperature");
var humidityDisplay = document.getElementById("humidity");
var percent = cnt.innerText;
var interval;

interval = setInterval(function() {
    percent++;
    cnt.innerHTML = percent;

    var temperature = Math.floor(Math.random() * 100); 
    var humidity = Math.floor(Math.random() * 100);
    temperatureDisplay.innerHTML = temperature + " °C";
    humidityDisplay.innerHTML = humidity + " %";
    
    var sphereSize = humidity * 1.8; 
    sphereInner.style.width = sphereSize + "px";
    sphereInner.style.height = sphereSize + "px";

    if (percent == 100) {
        clearInterval(interval);
    }
}, 60);