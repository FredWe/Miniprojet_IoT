<?php
error_reporting(E_ALL);

echo "<h2>TCP/IP Connection</h2>\n";
echo "<pre>";

/* Get the port for the WWW service. */
$service_port = 9999;
/* Get the IP address for the target host. */
$address = gethostbyname('localhost');

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$obj_received = null;
echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    $obj_received = json_decode($out);
    echo var_dump($obj_received);
}

echo "\nClosing socket...";
socket_close($socket);
echo "OK.\n\n";
echo "</pre>";

echo "<pre>";
//------part mysqli------//

$servername = "localhost";
$username = "pi";
$password = "raspberry";
$dbname = "develop";

// Connection established
$conn = new mysqli($servername, $username, $password, $dbname);

// Connection detected
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully\n";

$sql = "INSERT INTO arduino_mesure (mesure_time, pressure, temperature)
VALUES ($obj_received->time, $obj_received->pres, $obj_received->ambT)";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

$conn->close();
echo "</pre>";
//------part openweather API------//

?>
