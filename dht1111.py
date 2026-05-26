import time
import board
import adafruit_dht
import mysql.connector
from datetime import datetime

dhtDevice = adafruit_dht.DHT11(board.D5)

conn = mysql.connector.connect(
    host="localhost",
    user="admin",
    password="admin",
    database="serre_db"
)
cursor = conn.cursor()



while True:
    try:
        temperature = dhtDevice.temperature
        humidity = dhtDevice.humidity
        horaire = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        # Insertion dans la table temperature
        cursor.execute("""
            INSERT INTO temperature (horaire, valeur)
            VALUES (%s, %s)
        """, (horaire, temperature))

        # Insertion dans la table humidite
        cursor.execute("""
            INSERT INTO humidite (horaire, valeur)
            VALUES (%s, %s)
        """, (horaire, humidity))

        conn.commit()
        print(f"[{horaire}] {temperature}°C  {humidity}% → inséré")

    except RuntimeError as error:
        print(error)

    time.sleep(5)







