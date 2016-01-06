<?php
// function.php

function getDataFromSocket($address, $servicePort)
{
    $objReceived = null;
    /* Create a TCP/IP socket. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    } else {
        echo "OK.\n";
    }
    
    echo "Attempting to connect to '$address' on port '$servicePort' ...";
    $result = socket_connect($socket, $address, $servicePort);
    if ($result === false) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        echo "OK.\n";
    }
    
    echo "Reading response:\n\n";
    while ($out = socket_read($socket, 2048)) {
        $objReceived = json_decode($out);
        echo var_dump($objReceived);
    }
    
    echo "\nClosing socket...";
    socket_close($socket);
    echo "OK.\n\n";

    return($objReceived);
}

function executeQuery($sqlServerName, $userName, $password, $dbName, $sqlQuery)
{
    // Connection established
    $conn = new mysqli($sqlServerName, $userName, $password, $dbName);
   
    // Connection detected
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully\n";
   
    if (mysqli_query($conn, $sqlQuery)) {
        echo "New record created successfully\n";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
   
    $conn->close();
}

function getDataFromAPI($zip, $apikey)
{
    $handle = fopen('http://api.openweathermap.org/data/2.5/weather?zip='.$zip.',fr&units=metric&appid='.$apikey,'r');
    $content = "";
    while (!feof($handle)) {
        $content .= fread($handle, 10000);
    }
    fclose($handle);
    
    $objReceived = json_decode($content);
    echo var_dump(json_decode($content));

    return $objReceived;
}

function calcAltitude($pressure, $pressureRef)
{
     $A = $pressure / $pressureRef;
     $B = 1 / 5.25588;
     $C = pow ($A, $B);
     $C = 1 - $C;
     $C = $C / 0.0000225577;
     return $C;
}

