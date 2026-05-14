// =====================================================
// SMART PARKING ESP32 + LARAVEL API
// FINAL STABLE VERSION
// =====================================================

#include <WiFi.h>
#include <HTTPClient.h>

#include <SPI.h>
#include <MFRC522.h>
#include <ESP32Servo.h>

#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>

// =====================================================
// OLED
// =====================================================

#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64

Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, -1);

// =====================================================
// WIFI
// =====================================================

const char* ssid = "Sakila";
const char* password = "qory3010";

// =====================================================
// API
// =====================================================

String serverName = "http://192.168.1.8:8000/api/v1";

// =====================================================
// RFID
// =====================================================

// RFID MASUK
#define SS_ENTRANCE 5
#define RST_ENTRANCE 16

// RFID KELUAR
#define SS_EXIT 17
#define RST_EXIT 33

MFRC522 rfidMasuk(SS_ENTRANCE, RST_ENTRANCE);
MFRC522 rfidKeluar(SS_EXIT, RST_EXIT);

// =====================================================
// SPI RFID
// =====================================================

#define SCK_PIN 18
#define MISO_PIN 19
#define MOSI_PIN 23

// =====================================================
// SENSOR ULTRASONIK
// =====================================================

#define TRIG1 13
#define ECHO1 12

#define TRIG2 14
#define ECHO2 27

#define TRIG3 26
#define ECHO3 34

#define TRIG4 32
#define ECHO4 35

// =====================================================
// SERVO
// =====================================================

#define SERVO_MASUK 4
#define SERVO_KELUAR 15

Servo servoMasuk;
Servo servoKeluar;

// =====================================================
// BUZZER
// =====================================================

#define BUZZER_PIN 25

// =====================================================
// BATAS JARAK
// =====================================================

#define BATAS_JARAK 8.0

// =====================================================
// TIMER SENSOR
// =====================================================

unsigned long lastSensorSend = 0;
const unsigned long sensorInterval = 5000;

// =====================================================
// CACHE SLOT
// =====================================================

int lastSlot1 = -1;
int lastSlot2 = -1;
int lastSlot3 = -1;
int lastSlot4 = -1;

// =====================================================
// WIFI RECONNECT
// =====================================================

void reconnectWiFi()
{
  if (WiFi.status() != WL_CONNECTED)
  {
    Serial.println("Reconnect WiFi...");

    WiFi.disconnect();
    WiFi.begin(ssid, password);

    int retry = 0;

    while (WiFi.status() != WL_CONNECTED && retry < 20)
    {
      delay(500);
      Serial.print(".");
      retry++;
    }

    Serial.println("");

    if (WiFi.status() == WL_CONNECTED)
    {
      Serial.println("WiFi Reconnected");
      Serial.println(WiFi.localIP());
    }
  }
}

// =====================================================
// BUZZER
// =====================================================

void buzzerMasuk()
{
  digitalWrite(BUZZER_PIN, HIGH);
  delay(200);

  digitalWrite(BUZZER_PIN, LOW);
}

void buzzerKeluar()
{
  digitalWrite(BUZZER_PIN, HIGH);
  delay(150);

  digitalWrite(BUZZER_PIN, LOW);
  delay(100);

  digitalWrite(BUZZER_PIN, HIGH);
  delay(150);

  digitalWrite(BUZZER_PIN, LOW);
}

void buzzerDitolak()
{
  digitalWrite(BUZZER_PIN, HIGH);
  delay(1000);

  digitalWrite(BUZZER_PIN, LOW);
}

void buzzerPenuh()
{
  for (int i = 0; i < 3; i++)
  {
    digitalWrite(BUZZER_PIN, HIGH);
    delay(150);

    digitalWrite(BUZZER_PIN, LOW);
    delay(100);
  }
}

// =====================================================
// BACA UID RFID
// =====================================================

String bacaUID(MFRC522 &rfid)
{
  String uidString = "";

  for (byte i = 0; i < rfid.uid.size; i++)
  {
    if (rfid.uid.uidByte[i] < 0x10)
    {
      uidString += "0";
    }

    uidString += String(rfid.uid.uidByte[i], HEX);
  }

  uidString.toUpperCase();

  return uidString;
}

// =====================================================
// BACA JARAK
// =====================================================

float bacaJarak(int trigPin, int echoPin)
{
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);

  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);

  digitalWrite(trigPin, LOW);

  long durasi = pulseIn(echoPin, HIGH, 30000);

  if (durasi == 0)
  {
    return 999;
  }

  float jarak = durasi * 0.034 / 2;

  return jarak;
}

// =====================================================
// STATUS SLOT
// =====================================================

bool slotTerisi(float jarak)
{
  return (jarak > 0 && jarak < BATAS_JARAK);
}

// =====================================================
// SERIAL SLOT
// =====================================================

void cekSlot(int nomor, float jarak)
{
  Serial.print("Slot ");
  Serial.print(nomor);

  Serial.print(" = ");
  Serial.print(jarak);

  Serial.print(" cm --> ");

  if (slotTerisi(jarak))
  {
    Serial.println("TERISI");
  }
  else
  {
    Serial.println("KOSONG");
  }
}

// =====================================================
// KIRIM SENSOR
// =====================================================

void kirimSensor(String slot, bool status)
{
  if (WiFi.status() != WL_CONNECTED)
  {
    Serial.println("WiFi Disconnect");
    return;
  }

  HTTPClient http;

  String url = serverName + "/sensor";

  http.begin(url);

  http.addHeader("Content-Type", "application/json");

  http.setTimeout(5000);

  String body =
      "{\"slot_number\":\"" + slot +
      "\",\"status\":\"" + (status ? "terisi" : "kosong") + "\"}";

  Serial.println("======================");
  Serial.println("KIRIM SENSOR");

  Serial.println("URL:");
  Serial.println(url);

  Serial.println("BODY:");
  Serial.println(body);

  int httpCode = http.POST(body);

  Serial.print("HTTP SENSOR : ");
  Serial.println(httpCode);

  String response = http.getString();

  Serial.println("RESPONSE:");
  Serial.println(response);

  http.end();
}

// =====================================================
// KIRIM RFID
// =====================================================

bool kirimRFID(String uid)
{
  if (WiFi.status() != WL_CONNECTED)
  {
    Serial.println("WiFi Disconnect");
    return false;
  }

  HTTPClient http;

  String url = serverName + "/rfid";

  http.begin(url);

  http.addHeader("Content-Type", "application/json");

  http.setTimeout(5000);

  String body =
      "{\"rfid_tag\":\"" + uid + "\"}";

  Serial.println("======================");
  Serial.println("KIRIM RFID");

  Serial.println("URL:");
  Serial.println(url);

  Serial.println("BODY:");
  Serial.println(body);

  int httpCode = http.POST(body);

  Serial.print("HTTP CODE : ");
  Serial.println(httpCode);

  if (httpCode <= 0)
  {
    Serial.println("HTTP ERROR");

    http.end();

    return false;
  }

  String response = http.getString();

  Serial.println("RESPONSE:");
  Serial.println(response);

  http.end();

  if (httpCode == 200 &&
      response.indexOf("\"status\":\"success\"") >= 0)
  {
    return true;
  }

  return false;
}

// =====================================================
// SETUP
// =====================================================

void setup()
{
  Serial.begin(115200);

  // =====================================================
  // OLED
  // =====================================================

  Wire.begin(21, 22);

  if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C))
  {
    Serial.println("OLED GAGAL");

    while (1);
  }

  display.clearDisplay();

  display.setTextSize(2);
  display.setTextColor(WHITE);

  display.setCursor(10, 20);
  display.println("SMART");

  display.setCursor(10, 45);
  display.println("PARKING");

  display.display();

  delay(2000);

  // =====================================================
  // WIFI
  // =====================================================

  WiFi.begin(ssid, password);

  Serial.print("Connecting WiFi");

  while (WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi Connected");

  Serial.println(WiFi.localIP());

  // =====================================================
  // SPI RFID
  // =====================================================

  SPI.begin(SCK_PIN, MISO_PIN, MOSI_PIN);

  rfidMasuk.PCD_Init();
  rfidKeluar.PCD_Init();

  Serial.println("RFID READY");

  // =====================================================
  // SENSOR
  // =====================================================

  pinMode(TRIG1, OUTPUT);
  pinMode(ECHO1, INPUT);

  pinMode(TRIG2, OUTPUT);
  pinMode(ECHO2, INPUT);

  pinMode(TRIG3, OUTPUT);
  pinMode(ECHO3, INPUT);

  pinMode(TRIG4, OUTPUT);
  pinMode(ECHO4, INPUT);

  // =====================================================
  // SERVO
  // =====================================================

  servoMasuk.setPeriodHertz(50);
  servoKeluar.setPeriodHertz(50);

  servoMasuk.attach(SERVO_MASUK, 500, 2400);
  servoKeluar.attach(SERVO_KELUAR, 500, 2400);

  servoMasuk.write(90);
  servoKeluar.write(90);

  // =====================================================
  // BUZZER
  // =====================================================

  pinMode(BUZZER_PIN, OUTPUT);

  digitalWrite(BUZZER_PIN, LOW);

  Serial.println("=== SMART PARKING READY ===");
}

// =====================================================
// LOOP
// =====================================================

void loop()
{
  reconnectWiFi();

  // =====================================================
  // BACA SENSOR
  // =====================================================

  float jarak1 = bacaJarak(TRIG1, ECHO1);
  float jarak2 = bacaJarak(TRIG2, ECHO2);
  float jarak3 = bacaJarak(TRIG3, ECHO3);
  float jarak4 = bacaJarak(TRIG4, ECHO4);

  // =====================================================
  // STATUS SLOT
  // =====================================================

  bool slot1 = slotTerisi(jarak1);
  bool slot2 = slotTerisi(jarak2);
  bool slot3 = slotTerisi(jarak3);
  bool slot4 = slotTerisi(jarak4);

  // =====================================================
  // SERIAL MONITOR
  // =====================================================

  Serial.println("======================");

  cekSlot(1, jarak1);
  cekSlot(2, jarak2);
  cekSlot(3, jarak3);
  cekSlot(4, jarak4);

  // =====================================================
  // HITUNG SLOT KOSONG
  // =====================================================

  int slotKosong = 0;

  if (!slot1) slotKosong++;
  if (!slot2) slotKosong++;
  if (!slot3) slotKosong++;
  if (!slot4) slotKosong++;

  Serial.print("Jumlah Slot Kosong : ");
  Serial.println(slotKosong);

  // =====================================================
  // OLED
  // =====================================================

  display.clearDisplay();

  display.setTextSize(1);

  display.setCursor(0, 0);
  display.println("SMART PARKING");

  display.setTextSize(2);

  display.setCursor(0, 20);

  display.print("SLOT:");
  display.println(slotKosong);

  if (slotKosong == 0)
  {
    display.setTextSize(2);

    display.setCursor(0, 45);
    display.println("FULL");
  }
  else
  {
    display.setTextSize(1);

    display.setCursor(0, 55);
    display.println("SILAHKAN MASUK");
  }

  display.display();

  // =====================================================
  // KIRIM SENSOR
  // =====================================================

  if (millis() - lastSensorSend >= sensorInterval)
  {
    lastSensorSend = millis();

    if (slot1 != lastSlot1)
    {
      kirimSensor("Area A-1", slot1);
      lastSlot1 = slot1;
    }

    if (slot2 != lastSlot2)
    {
      kirimSensor("Area A-2", slot2);
      lastSlot2 = slot2;
    }

    if (slot3 != lastSlot3)
    {
      kirimSensor("Area A-3", slot3);
      lastSlot3 = slot3;
    }

    if (slot4 != lastSlot4)
    {
      kirimSensor("Area A-4", slot4);
      lastSlot4 = slot4;
    }
  }

  // =====================================================
  // RFID MASUK
  // =====================================================

  if (rfidMasuk.PICC_IsNewCardPresent() &&
      rfidMasuk.PICC_ReadCardSerial())
  {
    String uidMasuk = bacaUID(rfidMasuk);

    Serial.print("Kartu Masuk : ");
    Serial.println(uidMasuk);

    if (slotKosong == 0)
    {
      Serial.println("PARKIR PENUH");

      buzzerPenuh();
    }
    else
    {
      bool akses = kirimRFID(uidMasuk);

      if (akses)
      {
        Serial.println("AKSES MASUK DITERIMA");

        buzzerMasuk();

        servoMasuk.write(0);

        delay(5000);

        servoMasuk.write(90);
      }
      else
      {
        Serial.println("KARTU TIDAK VALID");

        buzzerDitolak();
      }
    }

    rfidMasuk.PICC_HaltA();
    rfidMasuk.PCD_StopCrypto1();
  }

  // =====================================================
  // RFID KELUAR
  // =====================================================

  if (rfidKeluar.PICC_IsNewCardPresent() &&
      rfidKeluar.PICC_ReadCardSerial())
  {
    String uidKeluar = bacaUID(rfidKeluar);

    Serial.print("Kartu Keluar : ");
    Serial.println(uidKeluar);

    bool akses = kirimRFID(uidKeluar);

    if (akses)
    {
      Serial.println("AKSES KELUAR DITERIMA");

      buzzerKeluar();

      servoKeluar.write(0);

      delay(5000);

      servoKeluar.write(90);
    }
    else
    {
      Serial.println("KARTU TIDAK VALID");

      buzzerDitolak();
    }

    rfidKeluar.PICC_HaltA();
    rfidKeluar.PCD_StopCrypto1();
  }

  delay(1000);
}
