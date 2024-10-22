

char ssid[] = "NOCHE DE TROPITANGO";             // nombre de tu red SSID
char pass[] = "47260765A";         // contraseña de tu red

char user[]         = "root";
char password[]     = "";

#include "DHT.h"
#include <Arduino.h>
#define PIN_CONEXION_DHT 25
#define TIPO_SENSOR DHT

DHT sensor(PIN_CONEXION_DHT, TIPO_SENSOR);

int ultimaVezLeido = 0;
long intervaloLectura = 6000;
unsigned long ultimaLecturaExitosa = 0;

float temperatura=0;
float humedad=0;
String INSERT_SQL;
String INSERT_SQL2;

#define MYSQL_DEBUG_PORT      Serial

// Nivel de depuración de 0 a 4
#define _MYSQL_LOGLEVEL_      1

#include <MySQL_Generic.h>

#define USING_HOST_NAME     false

#if USING_HOST_NAME
  char server[] = "tu_cuenta.ddns.net"; 
#else
  IPAddress server(192 , 168 , 0 , 1);


#endif

uint16_t server_port = 3306; 

char default_database[] = "base";       
char default_table[]    = "humedad";       
char default_table2[]    = "dht11";   


MySQL_Connection conn((Client *)&client);

void setup()
{
  
  Serial.begin(115200);
  sensor.begin();
  while (!Serial && millis() < 5000); 

  MYSQL_DISPLAY1("\nIniciando Basic_Insert_ESP en", ARDUINO_BOARD);
  MYSQL_DISPLAY(MYSQL_MARIADB_GENERIC_VERSION);

  // Comienza la sección de WiFi
  MYSQL_DISPLAY1("Conectando a", ssid);
  
  WiFi.begin(ssid, pass);
  
  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(500);
    MYSQL_DISPLAY0(".");
  }

  // imprime información sobre la conexión:
  MYSQL_DISPLAY1("Conectado a la red. Mi dirección IP es:", WiFi.localIP());

  MYSQL_DISPLAY3("Conectando al servidor SQL en", server, ", Puerto =", server_port);
  MYSQL_DISPLAY5("Usuario =", user, ", Contraseña =", password, ", Base de Datos =", default_database);
}

