<pre>
<?php

$firstname = htmlspecialchars($_POST["firstname"]);
$lastname = htmlspecialchars($_POST["lastname"]);
$password = htmlspecialchars($_POST["password"]);
echo "POST: \n" . $_POST . "\n";
echo var_dump($_POST) . "\n";
echo "GET: \n" . $_GET . "\n";
echo var_dump($_GET) . "\n";
echo "FIRSTNAME: $firstname \t LASTNAME: $lastname \t  PASSWORD: $password \n";

?>
</pre>
