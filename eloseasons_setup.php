<?php
//Only run this file when you want to take the final snapshot for a given season.

//Assign the current season ID.
$season = "0";
$i=0;

require ('csb_c.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//MFDV (Local Domain Database)
//CS (Remote Database)

$cs_el = "SELECT * FROM `cs_elo_primary` WHERE `elo` > '1000' ORDER BY `elo` DESC;";
$cs_elq = mysqli_query($mfdv_link, $cs_el);


while($mfdv_check = mysqli_fetch_array($cs_elq)){
    $i++;
    $rank = $i;
    $date = date('Y-m-d');
    $sid = $mfdv_check['steam_id'];
    $name = $mfdv_check['name'];
    $elo = $mfdv_check['elo'];
    $kills = $mfdv_check['kills'];
    $deaths = $mfdv_check['deaths'];

    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo "Name:" .$name." Rank:" .$rank."<br>";
$mfdv_update = "INSERT INTO `cs_elo_seasons` (`steam_id`, `name`, `elo`, `kills`, `deaths`, `season`, `rank`)	VALUES ('$sid','$name','$elo','$kills','$deaths', '$season', '$rank')";
mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}
?>