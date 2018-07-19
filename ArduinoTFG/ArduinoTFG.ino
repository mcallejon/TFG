#include < SoftwareSerial.h >
  SoftwareSerial gsmSerie(7, 8);
const int pinLed = 12;#include "DHT.h"#
define DHTPIN 2# define DHTTYPE DHT22
DHT dht(DHTPIN, DHTTYPE);
int a = 1;

void setup() {
  gsmSerie.begin(19200);
  Serial.begin(19200);

  Serial.println("Inicializando sistema y configurando GPRS");
  delay(2000);
  Serial.println("Todo Listo!");
  dht.begin();
  gsmSerie.flush();
  Serial.flush();

  //conectando 
  gsmSerie.println("AT+CGATT?");
  delay(100);
  toSerial();

  //Tipo de conexión seleccionado GPRS
  gsmSerie.println("AT+SAPBR=3,1,\"CONTYPE\",\"GPRS\"");
  delay(2000);
  toSerial();

  //Configuración APN Internet
  gsmSerie.println("AT+SAPBR=3,1,\"APN\",\"orangeworld\"");
  delay(1000);
  //gsmSerie.println("AT+SAPBR=3,1,\"USER\",\"orange\"");
  //gsmSerie.println("AT+SAPBR=3,1,\"PWD\",\"orange\"");

  delay(3000);
  toSerial();

  //Activar GPRS
  gsmSerie.println("AT+SAPBR=1,1");
  delay(5000);
  toSerial();

}

void loop() {

  //Variables, temperatura, humedad, dispositivo
  float t = dht.readTemperature(); //Lectura de la temperatura
  float h = dht.readHumidity(); //Lectura de la humedad 
  // definimos número de dispositivo
  int d = 1;

  //Pasamos info al monitor serie
  Serial.print("Temperatura: ");
  Serial.print(t);
  Serial.print(" *C ");
  Serial.print("Humedad Relativa: ");
  Serial.print(h);
  Serial.print(" % ");
  Serial.print("dispositivo: ");
  Serial.print(d);
  Serial.print("alerta: ");
  Serial.print(a);
  Serial.print('\n');

  //Condicional para determinar el envío o no del SMS de alerta, acionamiento del riego o caso inverso

  switch (a) {
  case 1:

    if (t > 20 && h < 70) {
      a = 2;
      enviaSms();
      riegoON();
      Serial.println("riego ON");
    }
    break;

  case 2:

    if (t > 4) {
      a = 1;
      enviaSms2();
      riegoOFF();
      Serial.println("riego OFF");
    }
    break;

  }
  //llamamos a la función enviaDatos, pásamos parámetros y realizamos transmisión al servidor por http
  enviaDatos(t, h, d, a);

}

void toSerial() {
  while (gsmSerie.available() != 0) {
    Serial.write(gsmSerie.read());
  }
}

void enviaDatos(float t, float h, int d, int a) {
  //Iniciamos servicio HTTP
  gsmSerie.println("AT+HTTPINIT");
  delay(2000);
  toSerial();

  // Enviamos petción GET y pásamos los parametros de los sensores y num de dispositivo
  gsmSerie.print("AT+");
  gsmSerie.print("HTTPPARA=\"URL\",");
  gsmSerie.print("\"http://90.171.51.220/tfg/insert.php");
  gsmSerie.print("?temp=");
  gsmSerie.print(t);
  gsmSerie.print("&hum=");
  gsmSerie.print(h);
  gsmSerie.print("&disp=");
  gsmSerie.print(d);
  gsmSerie.print("&disp=");
  gsmSerie.print(a);
  gsmSerie.println("\"");
  delay(2000);
  toSerial();

  //Seleccionamos el 0 que corresponde al GET
  gsmSerie.println("AT+HTTPACTION=0");
  delay(6000);
  toSerial();

  //Terminamos la conexión HTTP
  gsmSerie.println("");
  gsmSerie.println("AT+HTTPTERM");
  toSerial();
  delay(300);
  gsmSerie.println("");
  delay(1000);
}

void enviaSms() {
  Serial.println("INciando envío de SMS");

  //comando de envío de mensaje
  gsmSerie.print("AT+CMGF=1\r");
  delay(1000);

  //Número de destino y texto del mensaje
  gsmSerie.println("AT+CMGS=\"689201264\"");
  gsmSerie.println("se ha activado el riego");
  delay(1000);

  //Finalización
  gsmSerie.println((char) 26);
  delay(100);
  gsmSerie.println();
  delay(5000);
  Serial.println("SMS enviado");
}

void enviaSms2() {
  Serial.println("Iniciando envío de SMS");

  //comando de envío de mensaje
  gsmSerie.print("AT+CMGF=1\r");
  delay(1000);

  //Número de destino y texto del mensaje
  gsmSerie.println("AT+CMGS=\"689201264\"");
  gsmSerie.println("fin de la alerta");
  delay(100);

  //Finalización
  gsmSerie.println((char) 26);
  delay(100);
  gsmSerie.println();
  delay(5000);
  Serial.println("SMS enviado");
}

void riegoON() {
  digitalWrite(pinLed, HIGH);
}

void riegoOFF() {
  digitalWrite(pinLed, LOW);
}

