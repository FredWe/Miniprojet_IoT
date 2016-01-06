<?php
echo '<pre>';
$zip = 94200;
$apikey = '43a7f9544874c81b300ada438276d43f';

$handle = fopen('http://api.openweathermap.org/data/2.5/weather?zip='.$zip.',fr&units=metric&appid='.$apikey,'r');
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);

var_dump(json_decode($content));
echo '</pre>';
?>
