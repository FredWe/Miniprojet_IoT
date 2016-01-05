<?php
echo '<pre>';
$zip = 94200;

$handle = fopen("http://api.openweathermap.org/data/2.5/weather?zip=".$zip.",fr&units=metric&appid=2de143494c0b295cca9337e1e96b00e0","r");
$content = "";
//while (!feof($handle)) {
	$content .= fread($handle, 10000);
//    }
fclose($handle);

var_dump(json_decode($content));
echo '</pre>';
?>
