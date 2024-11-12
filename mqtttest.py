import json
import paho.mqtt.client as mqtt
import mysql.connector
from mysql.connector import Error

# Database configuration
DB_CONFIG = {
    'host': '133.hosttech.eu',       # e.g., 'localhost' or the server's IP
    'user': 'dreamteam',             # e.g., 'root' or your MariaDB username
    'password': '69Zrw!l48',     # MariaDB password
    'database': 'dreamteam'    # Name of the database to use
}

# MQTT configuration
MQTT_BROKER = "mqtt.tipp.space"  # Replace with your MQTT broker address
MQTT_PORT = 1883                           # Default MQTT port
MQTT_TOPIC = "probenlokal/temp/state/is"   # Topic to subscribe to
MQTT_TOPIC_ACK = "probenlokal/temp/state/target_ack"

# Connect to the MariaDB database
def connect_db():
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        if connection.is_connected():
            print("Connected to MariaDB")
        return connection
    except Error as e:
        print("Error connecting to MariaDB:", e)
        return None

# Insert value into database
def save_to_db(value):
    connection = connect_db()
    if connection:
        try:
            cursor = connection.cursor()
            # Insert the temperature value (assuming the table is named `temperature_data` and the column `value`)
            cursor.execute("INSERT INTO temperature (temperature) VALUES (%s)", (value,))
            connection.commit()
            print(f"Saved value {value} to database.")
        except Error as e:
            print("Error inserting data:", e)
        finally:
            cursor.close()
            connection.close()

def ack_in_db(value):
    connection = connect_db()
    if connection:
        try:
            # connection cursor with dictionary
            cursor = connection.cursor(dictionary=True)
            # Select the last inserted value if it has created_at within the last 5 minutes
            cursor.execute("SELECT * FROM set_temperature WHERE created_at >= NOW() - INTERVAL 5 MINUTE ORDER BY id DESC LIMIT 1")
            last_value = cursor.fetchone()
            # if last_value == value update ack to 1
            if float(last_value["temperature"]) == float(value):
                cursor.execute("UPDATE set_temperature SET ack = 1 WHERE id = " + str(last_value["id"]))
                connection.commit()
                print(f"Updated ack for value {value} to 1.")
            else:
                print(f"Value {value} not found in database.")
        except Error as e:
            print("Error updating ack:", e)
        finally:
            cursor.close()
            connection.close()


# MQTT callbacks
def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print("Connected to MQTT Broker!")
        client.subscribe(MQTT_TOPIC)
        client.subscribe(MQTT_TOPIC_ACK)
    else:
        print("Failed to connect, return code %d\n", rc)

def on_message(client, userdata, msg):
    try:
        # if topic is ack, do nothing
        if msg.topic == MQTT_TOPIC_ACK:
            ack_in_db(msg.payload.decode())
        
        if msg.topic == MQTT_TOPIC:
            payload = msg.payload.decode()
            print(payload)
            data = json.loads(payload)
            print(data)

            # Extract the value (adjust if your JSON structure is different)
            value = payload
            if value is not None:
                save_to_db(value)
            else:
                print("No 'value' field in payload:", payload)
    except json.JSONDecodeError:
        print("Failed to decode JSON:", msg.payload)

# MQTT client setup
client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message

# Connect to MQTT broker with user and pw
client.username_pw_set("admin", "cEhg4ACczfCWjuzb9zrh")
client.connect(MQTT_BROKER, MQTT_PORT, 60)

# Run indefinitely
client.loop_forever()