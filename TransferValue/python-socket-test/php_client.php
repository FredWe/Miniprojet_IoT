if(isset($_SESSION['id'])) 
{
    // Put stored session variables into local PHP variable
    $uid = $_SESSION['id'];
    $usname = $_SESSION['username'];
    $result = "Login data: <br /> Username: ".$usname. "<br /> Id: ".$uid;

    error_reporting(E_ALL);

    //  Allow the script to hang around waiting for connections.
    set_time_limit(0);

    // Turn on implicit output flushing so we see what we're getting as it comes in.
    ob_implicit_flush();

    // Set timeout in seconds
    $timeout = 3;  

    // Create a TCP/IP client socket.
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) 
    {
        $result2 = "Error: socket_create() failed: reason: " .socket_strerror(socket_last_error()). "\n";
    }

    // Server data
    $host = '127.0.0.1';
    $port = 50007;

    $error = NULL;
    $attempts = 0;
    $timeout *= 1000;  // adjust because we sleeping in 1 millisecond increments
    $connected = FALSE;
    while (!($connected = socket_connect($socket, $host, $port)) && ($attempts++ < $timeout)) 
    {
        $error = socket_last_error();
        if ($error != SOCKET_EINPROGRESS && $error != SOCKET_EALREADY) 
        {
            echo "Error Connecting Socket: ".socket_strerror($error) . "\n";
            socket_close($socket);
            return NULL;
        }
        usleep(1000);
    }

    if (!$connected) 
    {
        echo "Error Connecting Socket: Connect Timed Out After " . $timeout/1000 . " seconds. ".socket_strerror(socket_last_error()) . "\n";
        socket_close($socket);
        return NULL;
    }

    // Write to the socket
    //$output="Client Logged on via website" ;
    //socket_write($socket, $output, strlen ($output)) or die("Could not write output\n");

    // Get the response from the server - our current telemetry
    $resultLength = socket_read($socket, 1024) or die("Could not read server response\n");
    $result4 = $resultLength;

    if($result4 === "Enabled")
    {
        echo "Alarm is Running";
        $disabled1 = "disabled='disabled'";
        $disabled2 = "";
    }
    elseif($result4 === "Disabled")
    {
       echo "Alarm is not running";
       $disabled1 = "";
       $disabled2 = "disabled='disabled'";
    }

    // close the socket
    socket_close($socket);
}
else 
{
    $result = "You are not logged in yet";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $usname ;?> - Alarm Enable/Disable</title>
</head>
<body>
<br>
<?php
echo $result;
?>
<br>
<?php
echo $result2;
?> 
<br>
<form id="form" action="user.php" method="post" enctype="multipart/form-data">
<input type='submit' name='submit1' value='Enable Alarm' <?php echo $disabled1; ?> />
<input type='submit' name='submit2' value='Disable Alarm' <?php echo $disabled2; ?> />
</form>
<article>
<?php
   if (isset($_POST[submit1])) 
   {
        /*//  Allow the script to hang around waiting for connections.
        set_time_limit(0);

        // Turn on implicit output flushing so we see what we're getting as it comes in.
        ob_implicit_flush();

        // Set timeout in seconds
        $timeout = 3;  

        // Create a TCP/IP client socket.
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) 
        {
            $result2 = "Error: socket_create() failed: reason: " .socket_strerror(socket_last_error()). "\n";
        }

        // Server data
        $host = '127.0.0.1';
        $port = 50007;

        $error = NULL;
        $attempts = 0;
        $timeout *= 1000;  // adjust because we sleeping in 1 millisecond increments
        $connected = FALSE;
        while (!($connected = socket_connect($socket, $host, $port)) && ($attempts++ < $timeout)) 
        {
            $error = socket_last_error();
            if ($error != SOCKET_EINPROGRESS && $error != SOCKET_EALREADY) 
            {
                echo "Error Connecting Socket: ".socket_strerror($error) . "\n";
                socket_close($socket);
                return NULL;
            }
            usleep(1000);
        }
        */

        if (!$connected) 
        {
            echo "Error Connecting Socket: Connect Timed Out After " . $timeout/1000 . " seconds. ".socket_strerror(socket_last_error()) . "\n";
            socket_close($socket);
            return NULL;
        }
        // Write to the socket
        $input="Enable";
        socket_write($socket, $input, strlen ($input)) or die("Could not write input\n");
        echo "Send Enable back into socket to the Server";

        // close the socket
        socket_close($socket);

        // Now direct to user feed
        header("Location: logout.php");
   }
   if (isset($_POST[submit2])) 
   {
        /*//  Allow the script to hang around waiting for connections.
        set_time_limit(0);

        // Turn on implicit output flushing so we see what we're getting as it comes in.
        ob_implicit_flush();

        // Set timeout in seconds
        $timeout = 3;  

        // Create a TCP/IP client socket.
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) 
        {
            $result2 = "Error: socket_create() failed: reason: " .socket_strerror(socket_last_error()). "\n";
        }

        // Server data
        $host = '127.0.0.1';
        $port = 50007;

        $error = NULL;
        $attempts = 0;
        $timeout *= 1000;  // adjust because we sleeping in 1 millisecond increments
        $connected = FALSE;
        while (!($connected = socket_connect($socket, $host, $port)) && ($attempts++ < $timeout)) 
        {
            $error = socket_last_error();
            if ($error != SOCKET_EINPROGRESS && $error != SOCKET_EALREADY) 
            {
                echo "Error Connecting Socket: ".socket_strerror($error) . "\n";
                socket_close($socket);
                return NULL;
            }
            usleep(1000);
        }
        */
        if (!$connected) 
        {
            echo "Error Connecting Socket: Connect Timed Out After " . $timeout/1000 . " seconds. ".socket_strerror(socket_last_error()) . "\n";
            socket_close($socket);
            return NULL;
        }  
        // Write to the socket
        $input="Disable";
        socket_write($socket, $input, strlen ($input)) or die("Could not write input\n");
        echo "Send Disable back into socket to the Server";

        // close the socket
        socket_close($socket);

        // Now direct to user feed
        header("Location: logout.php");        
   }
?>
</article>
<br>
<a href="logout.php">Logout</a>
</body>
</html>