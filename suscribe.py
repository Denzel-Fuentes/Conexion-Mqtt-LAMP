#import context  
import paho.mqtt.client as mqtt

def on_message(mosq, obj, msg):
    print(msg.topic + " " + str(msg.qos) + " " + str(msg.payload))

mqttc = mqtt.Client()
mqttc.username_pw_set("denzel", "denzel")  # Agrega el usuario y contraseña
mqttc.on_message = on_message
mqttc.connect("localhost", 1883, 60)
mqttc.subscribe("#", 0)

mqttc.loop_forever()
