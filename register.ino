#include <WiFi.h>
#include <ESP8266HTTPClient.h>
#include <DHT.h>
#include <SEN0114.h>

// Datos de conexión a Wi-Fi y base de datos
const char* ssid = "root";
const char* password = "";
const char* host = "locahost";
const int port = 80;
const String url = "/guardar_datos.php";

// Pines y objetos de los sensores
const int dhtPin = 2;
const int sen0114Pin = A0;
DHT dht(dhtPin, DHT11);
SEN0114 sen0114;

// Función para enviar datos al servidor
void enviarDatos(float temperatura, float humedadSuelo) {
  String url_complete = "http://" + String(host) + ":" + String(port) + url + "?temperatura=" + String(temperatura) + "&humedad_suelo=" + String(humedadSuelo);

  HTTPClient http;
  http.begin(url_complete);
  int httpCode = http.GET();

  if (httpCode > 0) {
    Serial.printf("[HTTP] GET... code: %d\n", httpCode);
  } else {
    Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
  }

  http.end();
}

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");

  dht.begin();
  sen0114.begin();
}

void loop() {
  // Leer datos de los sensores
  float temperatura = dht.readTemperature();
  float humedadSuelo = sen0114.readHumidity();

  if (isnan(temperatura) || isnan(humedadSuelo)) {
    Serial.println("Fallo al leer los sensores!");
    return;
  }

  Serial.print("Temperatura: ");
  Serial.print(temperatura);
  Serial.print("°C  ");
  Serial.print("Humedad suelo: ");
  Serial.println(humedadSuelo);

  // Enviar datos al servidor
  enviarDatos(temperatura, humedadSuelo);

  delay(2000); // Espera 2 segundos antes de la siguiente lectura
}