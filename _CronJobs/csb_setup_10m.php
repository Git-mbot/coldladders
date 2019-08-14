<script>
function toCommunityID($id, $type = 1, $instance = 1) {
	if (preg_match('/^STEAM_/', $id)) {
			$parts = explode(':', str_replace('STEAM_', '', $id));
			$universe = (int)$parts[0];
			if ($universe == 0)
					$universe = 1;
			$steamID = ($universe << 56) | ($type << 52) | ($instance << 32) | ((int)$parts[2] << 1) | (int)$parts[1];
			return $steamID;
	} elseif (is_numeric($id) && strlen($id) < 16) {
			return (1 << 56) | ($type << 52) | ($instance << 32) | $id;
	} else {
			return $id; // We have no idea what this is, so just return it.
	}
}
</script>

<?php
//These update every 10 minutes.
require ('csb_c.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//MFDV (Local Domain Database)
//Clear the primary DB now that data has been migrated.
$mfdv_clear_primary = "TRUNCATE TABLE `cs_banks_primary`;";
mysqli_query($mfdv_link, $mfdv_clear_primary);


//Grab & Push latest data from CS DB to the primary snapshot table.[Latest]
$cs_banks = "SELECT * FROM `bluerp_players` WHERE `bank` > '50000' ORDER BY `bank` DESC;";
$csq_banks = mysqli_query($cs_link, $cs_banks);

while($csq_check = mysqli_fetch_array($csq_banks)){
    $sid = $csq_check['steam_id'];  
    $cash = $csq_check['cash']; 
    $bank = $csq_check['bank'];
    $income = $csq_check['income'];
    $exp = $csq_check['experience'];
    $res = $csq_check['respect']; 
    $minutes = $csq_check['minutes']; 

    $name = $csq_check['username'];
    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo '<br> Inserting '.$sid.' into CSB primary.';
    echo "<br>Name:" .$name."<br>";
    echo $minutes;
    $mfdv_update = "INSERT INTO `cs_banks_primary` (`steam_id`, `cash`, `bank`, `income`, `experience` , `respect` , `name` , `minutes` )	VALUES ('$sid','$cash','$bank','$income','$exp','$res','$name','$minutes')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}


//******************************//
//START OF ELO TABLE
//******************************//

//MFDV (Local Domain Database)
//CS (Remote Database)
//Clear the primary DB now that data has been migrated.
$mfdv_clear_primary = "TRUNCATE TABLE `cs_elo_primary`;";
mysqli_query($mfdv_link, $mfdv_clear_primary);


//Grab & Push latest data from CS DB to the primary snapshot table.[Latest]

$cs_elo = "SELECT `bluerp_deathmatching`.`steam_id`, `bluerp_deathmatching`.`elo_rating`, `bluerp_deathmatching`.`player_kills`, `bluerp_deathmatching`.`player_deaths`, `bluerp_players`.`username`
FROM bluerp_deathmatching
INNER JOIN bluerp_players ON `bluerp_deathmatching`.`steam_id`=`bluerp_players`.`steam_id`  WHERE `bluerp_deathmatching`.`elo_rating` > '1000'
ORDER BY `bluerp_deathmatching`.`elo_rating` DESC LIMIT 100";

$csq_elo = mysqli_query($cs_link, $cs_elo);             
while($csq_check = mysqli_fetch_array($csq_elo)){

    $sid = $csq_check['steam_id'];
    $name = $csq_check['username'];
    $elo = $csq_check['elo_rating'];
    $kills = $csq_check['player_kills'];
    $deaths = $csq_check['player_deaths'];

    $name = $csq_check['username'];

    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo '<br> Inserting '.$sid.' into CSE primary.';
    echo "<br>Name:" .$name."";
    $mfdv_update = "INSERT INTO `cs_elo_primary` (`steam_id`, `name`, `elo`, `kills`, `deaths`)	VALUES ('$sid','$name','$elo','$kills','$deaths')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}


//******************************//
//END OF ELO TABLE
//******************************//
?>