import serial
import requests

urls = "http://127.0.0.1/savedb.php"
arduino = serial.Serial("COM4", timeout=1, baudrate=9600)

while True:
    a = arduino.readline().decode("utf-8").strip('\n').strip('\r')
    if(a!=''):
        temp = a.split(';')
        data = {'Tt':float(temp[1]),
                'Hh':float(temp[0]),
                'Pp':float(temp[2])}
        res = requests.post(url = urls, data = data)
        print(res.text)
