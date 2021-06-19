import serial
import numpy as np
import pandas as pd
import datetime

# modify based on the dataset directory folder
filepath = r"C:\dataPC.csv"

# load the data and finish the cleaning process
train = pd.read_csv(filepath)

print(train.isnull().sum())
print(train.head())
print(train.shape)

X = train.drop(columns=['Precipitation'])
y = train.Precipitation

# choose algorithm and model fitting
from sklearn.tree import DecisionTreeClassifier
from sklearn import tree

clf = tree.DecisionTreeClassifier(criterion='entropy', max_depth=5)

print("Training Model, Time : ")
now = datetime.datetime.now()
print(now.strftime('%Y-%m-%d %H:%M:%S.%f')[:-3])
clf.fit(X, y)
now = datetime.datetime.now()
print(now.strftime('%Y-%m-%d %H:%M:%S.%f')[:-3])
print("__________________________________")

print("Running UNO")
now = datetime.datetime.now()
print(now.strftime('%Y-%m-%d %H:%M:%S.%f')[:-3])

# modify based on proteus
arduino = serial.Serial("COM4", timeout=1, baudrate=9600)

while True:
    a = arduino.readline().decode("utf-8").strip('\n').strip('\r')
    if (a != ''):
        temp = a.split(';')
        data = np.array([[float(temp[0]), float(temp[1]), float(temp[2])]])
        res = clf.predict(data)
        print("Data : {}, {} Coming".format(data, res))
