import time
import board
import busio
import adafruit_ads1x15.ads1115 as ADS
from adafruit_ads1x15.analog_in import AnalogIn
import mysql.connector
from datetime import datetime

# Capteur ADS1115
i2c = busio.I2C(board.SCL, board.SDA)
ads = ADS.ADS1115(i2c)
canal = AnalogIn(ads, 0)  # A0

# Connexion MariaDB
conn = mysql.connector.connect(
    host="localhost",
    user="admin",
    password="admin",
    database="serre_db"
)
cursor = conn.cursor()

# Valeurs min/max du capteur à calibrer si besoin
VALEUR_MIN = 0      # capteur dans l'air (sec)
VALEUR_MAX = 32767  # capteur dans l'eau (trempé)

while True:
    try:
        valeur_brute = canal.value
        pourcentage = round((valeur_brute - VALEUR_MIN) / (VALEUR_MAX - VALEUR_MIN) * 100, 1)
        pourcentage = max(0.0, min(100.0, pourcentage))  # clamp entre 0 et 100

        horaire = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

        if pourcentage < 30:
            etat = "Sec"
        elif pourcentage < 70:
            etat = "Humide"
        else:
            etat = "Trempé"

        cursor.execute("""
            INSERT INTO humidite_sol (horaire, valeur)
            VALUES (%s, %s)
        """, (horaire, pourcentage))

        conn.commit()
        print(f"[{horaire}] Humidité sol: {pourcentage}%")

    except Exception as error:
        print(f"Erreur: {error}")

    time.sleep(5)
