#include <WiFi.h>
#include <ESPAsyncWebServer.h>
#include <DHT.h>
#include <WebSocketsServer.h>
#include <SPIFFS.h>
// Credenciales
const char *ssid = "Pascual";
const char *password "admin123";
// Servidores
  AsyncWebServer server(80);
  WebSocketsServer webSocket(81);
    const unsigned long sendInterval = 1000;
// Sensor de humedad de suelo
    const uint16_t dry 4095; // Valor de cal para sensor seco
    const uint16_t wet 1600; // valor cal para sensor húmedo
  const uint8_t sensorPin = 35;
  uint16_t sensorReading;

  void loop() 
  webSocket.loop(); // WebSocket events
{
      static uint32_t prevMillis = 0;
    if (millis() prevMillis sendInterval){ 
      prevMillis= millis();
      sensorReading analogRead(sensorPin);
      uint16_t moisturePercentage = map(sensorReading, wet, dry, 100, 0); moisturePercentage = constrain (moisturePercentage, 0, 100);
      String status;
      if (moisturePercentage <= 25)
        status = "Suelo muy seco Regar!";
      else if (moisture Percentage >= 25 8& moisturePercentage < 70)
        status = "Humedad de Suelo Ideal";
      else
        status "Suelo demasiado HUMEDO!"

    sendData(moisturePercentage, status);
    }
}


/* 

                    <div class="sphere-box">
            <div class="sphere">
                <div class="sphere-inner" id="sphere-inner">
                  <div class="percent">
                        <div class="percentNum" id="count">0</div>
                        <div class="percentB">%</div>
                    </div>
                </div>
            </div>
        </div>
*/