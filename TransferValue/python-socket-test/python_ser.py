import time
import socket
import logging

def socketCon():
    LOG_FILENAME = "logging.out"
    logging.basicConfig(filename=LOG_FILENAME,level=logging.DEBUG,)
    logging.info("Started setting up the socket to php connection")    

    HOST = '127.0.0.1'                 # Symbolic name meaning the local host
    PORT = 50007              # Arbitrary non-privileged port
    s = None
    for res in socket.getaddrinfo(HOST, PORT, socket.AF_UNSPEC, socket.SOCK_STREAM, 0, socket.AI_PASSIVE):
        af, socktype, proto, canonname, sa = res
        try:
            s = socket.socket(af, socktype, proto)
            logging.info("Connected to Server")
        except socket.error, msg:
            logging.info('Socket Error Code : ' + str(msg[0]) + ' Message ' + msg[1])
            s = None
            continue
        try:
            s.bind(sa)
            logging.info("Bind Complete")
            s.listen(1)
            logging.info("Now Listening to socket")
        except socket.error, msg:
            logging.info('Socket bind/listening Error Code : ' + str(msg[0]) + ' Message ' + msg[1])     
            s.close()
            s = None
            continue
        break
    if s is None:
        logging.info("could not open socket")

    #try:
    logging.info("Waiting on Socket to Accept")
    conn, addr = s.accept()
    logging.info("Connected by "+str(addr))

    # Get data from the socket
    #data1 = conn.recv(1024)
    #logging.info("What did the user send from the Website: "+str(data1))

    # Send data to socket
    alarm = "Enabled"
    conn.send(alarm)
    logging.info("Send status to client socket: "+str(alarm))

    run = True
    logging.info("Waiting for user button press")
    # Wait for user button press from website
    while run == True:
        # Get the button press from the website
        data2 = conn.recv(1024)
        logging.info("Recieving data: "+str(data2))
        if data2 == 0:
            logging.info("What did the user select from the Website: "+str(data2))
            run = False

    # close the socket
    conn.close()

def runTest():
    #while:
    try:   
        socketCon()
    except:
        print "There was a problem"

socketCon()        
#runTest()