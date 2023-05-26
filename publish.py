import paho.mqtt.publish as publish

auth = {'username': "denzel", 'password': "denzel"}  # Agrega el usuario y contraseña
publish.single("boton_bool", "1", hostname="localhost", auth=auth)
publish.single("valor_analog", "357", hostname="localhost", auth=auth)
