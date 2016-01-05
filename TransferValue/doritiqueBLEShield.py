#*****************************************************************************************************
#
#                  Dong WEI - www.upmc.fr 03/01/2016
#
# Ce script est charge de recuperer les donnees des sondes du Arduino avec BLEShield via une connexion  
# bluetooth low energy (BLE). N'oubliez pas de modifier l'adresse BLE de votre BLEShield.
#
#*****************************************************************************************************

import os
import sys
import pexpect
import time
import urllib2
import urllib
import socket
import json
from ubidots import ApiClient

#Adresse BLE du BLEShield (pour la connaitre tapez la commande hcitool lescan)
ble_addr="E6:FC:99:AB:49:42"

#Classe du BLEShield 
class BLEShield:
   
  #Le constructeur 
  
  def __init__(self,ble_addr):
    self.ble_addr = ble_addr
  #Getter de la temperature
  def get_Data(self):
    self.child = pexpect.spawn('gatttool -b ' + ble_addr + ' -t random --interactive')
    self.child.expect('\[LE\]\>')
    print "Trying connection to BLE Shield..." 
    self.child.sendline('connect')
    self.child.expect('Connection successful')
    # Enabled de sensor reading capability
    self.child.sendline('char-write-cmd 0x0e A001')
    self.child.expect('\[LE\]\>')
    self.child.sendline('char-write-req 0x11 0100 -listen')
    self.child.expect('Characteristic value was written successfully')
    print 'Connection & Enable successful'

    #Active la sonde
    self.child.expect('value:( \w\w){6}')  
    print self.child.after 
    rval = self.child.after.split()

    self.ambT = int((rval[2] + rval[3]), 16) / float(100)
    self.pres = int((rval[4] + rval[5] + rval[6]), 16)

    print self.ambT, "\t", self.pres 
    
    self.child.sendline('exit')
    print "Connection BLE closed"
    return(self)

#*******************************************Fonction principale************************************ 
def main(): 
  # Create an "API" object
  api = ApiClient("569f9980ce8645bad6b1074aaca5b031a26de7d6")
  # Create a "Variable" object
  temperatureVariable = api.get_variable("5689b7577625423d12706ebd")
  pressureVariable = api.get_variable("5689b79b7625423fc1724bb4")
  
  # create a socket object
  serversocket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
  # get local machine name
  host = "localhost"
  port = 9999
  # bind to the port
  serversocket.bind((host, port))
  # queue up to 5 requests
  serversocket.listen(1)

  #Creation de l'instance et connection sur le BLEShield 
  bleshield = BLEShield(ble_addr)
  
  while True:
    # establish a connection
    clientsocket,addr = serversocket.accept()
    print("Got a connection from %s" % str(addr))

    #Recuperation de la temperature infra rouge
    tmpIR = bleshield.get_Data()
    print "tmpIR: ", vars(tmpIR) 
    
    #Transmission des donnees
    data = {
      'ambT' : tmpIR.ambT,
      'pres' : tmpIR.pres,
      'time' : time.time()
    }
    json_str = json.dumps(data)
    clientsocket.send(json_str.encode('ascii'))
    clientsocket.close()

    # Write the value to your variable in Ubidots
    temperatureVariable.save_value({'value' : tmpIR.ambT})
    pressureVariable.save_value({'value' : tmpIR.pres})

if __name__ == "__main__":
    main()		
