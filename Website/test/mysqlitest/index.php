<?php
error_reporting(E_ALL);

require 'config.php';
require 'function.php';

//------part get data------//
echo "<h2>TCP/IP Connection</h2>\n";
echo "<pre>";

$objSensorData = getDataFromSocket(SERVICE_ADDRESS, SERVICE_PORT);

echo "</pre>";

echo "<pre>";
//------part mysqli------//

$sql = "INSERT INTO arduino_mesure (mesure_time, pressure, temperature)
VALUES ($objSensorData->time, $objSensorData->pres, $objSensorData->ambT)";

executeQuery(SQL_SERVER_NAME, SQL_USERNAME, SQL_PASSWORD, SQL_SENSOR_DBNAME, $sql);

echo "</pre>";
//------part openweather API------//

echo '<pre>';

$objAPIData = getDataFromAPI(ZIPCODE, APIKEY);

$sql = "INSERT INTO openweather_metadata (
 base
,city_id
,city_name
,cod
,coord_lat
,coord_lon
,dt
,sys_country
,sys_id
,sys_message
,sys_sunrise
,sys_sunset
,sys_type
)
VALUES (
 '".$objAPIData->base."'
,".$objAPIData->id."
,'"."$objAPIData->name"."'
,".$objAPIData->cod."
,".$objAPIData->coord->lat."
,".$objAPIData->coord->lon."
,".$objAPIData->dt."
,'".$objAPIData->sys->country."'
,".$objAPIData->sys->id."
,".$objAPIData->sys->message."
,".$objAPIData->sys->sunrise."
,".$objAPIData->sys->sunset."
,".$objAPIData->sys->type."
)";
executeQuery(SQL_SERVER_NAME, SQL_USERNAME, SQL_PASSWORD, SQL_SENSOR_DBNAME, $sql);

$sql = "INSERT INTO openweather_main (
 dt
,humidity
,pressure
,temp
,temp_max
,temp_min
)
VALUES (
 ".$objAPIData->dt."
,".$objAPIData->main->humidity."
,".$objAPIData->main->pressure."
,".$objAPIData->main->temp."
,".$objAPIData->main->temp_max."
,".$objAPIData->main->temp_min."
)";
executeQuery(SQL_SERVER_NAME, SQL_USERNAME, SQL_PASSWORD, SQL_SENSOR_DBNAME, $sql);

$sql = "INSERT INTO openweather_weather (
 clouds_all
,dt
,weather_condition_id
,weather_description
,weather_icon
,weather_main
,wind_deg
,wind_speed
)
VALUES (
 ".$objAPIData->clouds->all."
,$objAPIData->dt
,".$objAPIData->weather[0]->id."
,'".$objAPIData->weather[0]->description."'
,'".$objAPIData->weather[0]->icon."'
,'".$objAPIData->weather[0]->main."'
,".$objAPIData->wind->deg."
,".$objAPIData->wind->speed."
)";
executeQuery(SQL_SERVER_NAME, SQL_USERNAME, SQL_PASSWORD, SQL_SENSOR_DBNAME, $sql);
echo '</pre>';
?>

