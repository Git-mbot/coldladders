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
//These update every 24 hours.
require ('csb_c.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//MFDV (Local Domain Database)
//CS (Remote Database)
//We grab the data from the primary snapshot table. [Latest]
$mfdv_banks = "SELECT * FROM `cs_banks_primary` WHERE `bank` > '50000' ORDER BY `bank` DESC;";
$mfdvq_banks = mysqli_query($mfdv_link, $mfdv_banks);

//Clear the secondary DB to prepare for new data.
$mfdv_clear_secondary = "TRUNCATE TABLE `cs_banks_secondary`;";
mysqli_query($mfdv_link, $mfdv_clear_secondary);

//Push the data taken from primary to secondary snapshot table. [Old Data]
while($mfdv_check = mysqli_fetch_array($mfdvq_banks)){
    $sid = $mfdv_check['steam_id'];
    $cash = $mfdv_check['cash'];
    $bank = $mfdv_check['bank'];
    $income = $mfdv_check['income'];
    $exp = $mfdv_check['experience'];
    $res = $mfdv_check['respect'];
    $name = $mfdv_check['name'];
    $minutes = $mfdv_check['minutes'];

    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo "<br>[Banks Secondary] Updating User:" .$name." ".$sid." ";
    echo $minutes;

    $mfdv_update = "INSERT INTO `cs_banks_secondary` (`steam_id`, `cash`, `bank`, `income`, `experience` , `respect` , `name` , `minutes`)	VALUES ('$sid','$cash','$bank','$income','$exp','$res','$name','$minutes')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}

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
    echo "<br>[Banks Primary] Updating User:" .$name." ".$sid." ";
    echo $minutes;
    $mfdv_update = "INSERT INTO `cs_banks_primary` (`steam_id`, `cash`, `bank`, `income`, `experience` , `respect` , `name` , `minutes` )	VALUES ('$sid','$cash','$bank','$income','$exp','$res','$name','$minutes')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}


//******************************//
//START OF ELO TABLE
//******************************//

//MFDV (Local Domain Database)
//CS (Remote Database)
//We grab the data from the primary snapshot table. [Latest]
$mfdv_elo = "SELECT * FROM `cs_elo_primary` WHERE `elo` > '1000' ORDER BY `elo` DESC LIMIT 100;";
$mfdvq_elo = mysqli_query($mfdv_link, $mfdv_elo);

//Clear the secondary DB to prepare for new data.
$mfdv_clear_secondary = "TRUNCATE TABLE `cs_elo_secondary`;";
mysqli_query($mfdv_link, $mfdv_clear_secondary);

//Push the data taken from primary to secondary snapshot table. [Old Data]
while($mfdv_check = mysqli_fetch_array($mfdvq_elo)){
    $sid = $mfdv_check['steam_id'];
    $name = $mfdv_check['name'];
    $elo = $mfdv_check['elo'];
    $kills = $mfdv_check['kills'];
    $deaths = $mfdv_check['deaths'];

    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo "<br>[ELO Secondary] Updating User Elo:" .$name." ".$sid."";

    $mfdv_update = "INSERT INTO `cs_elo_secondary` (`steam_id`, `name`, `elo`, `kills`, `deaths`)	VALUES ('$sid','$name','$elo','$kills','$deaths')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}

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
    echo "<br>[ELO Primary] Updating User Elo:" .$name." ".$sid."";
    $mfdv_update = "INSERT INTO `cs_elo_primary` (`steam_id`, `name`, `elo`, `kills`, `deaths`)	VALUES ('$sid','$name','$elo','$kills','$deaths')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}


//******************************//
//END OF ELO TABLE
//******************************//


//******************************//
//START OF RESPECT / EXP LADDERS
//******************************//

//Grab All relatedrespect / exp data from CS DB and Push to MFDV cs_banks_history [Latest]
$mfdv_clear_exp = "TRUNCATE TABLE `cs_expresp_ladder`;";
mysqli_query($mfdv_link, $mfdv_clear_exp);

$cs_er = "SELECT * FROM `bluerp_players` WHERE `experience` > '10' OR `respect` > '10'";
$cs_erq = mysqli_query($cs_link, $cs_er);    

while($csq_check = mysqli_fetch_array($cs_erq)){

    $sid = $csq_check['steam_id'];  
    $exp = $csq_check['experience'];
    $res = $csq_check['respect'];
    $name = $csq_check['username'];

    $name = mysqli_real_escape_string($mfdv_link, $name);

    echo "<br>[ITEMS] Updating User:" .$name." Exp:" .$exp. " Resp:" .$res."";
    $mfdv_update = "INSERT INTO `cs_expresp_ladder` (`steam_id`, `experience`, `respect`, `name`) VALUES ('$sid', '$exp','$res','$name')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}

//******************************//
//END OF RESPECT / EXP LADDERS
//******************************//

//******************************//
//START OF INVENTORY LADDERS
//******************************//

$mfdv_clear_items = "TRUNCATE TABLE `cs_items`;";
mysqli_query($mfdv_link, $mfdv_clear_items);
/*
$cs_items = "SELECT `bluerp_items`.`steam_id`, `bluerp_items`.`itemid`, `bluerp_items`.`quantity`, `bluerp_itemlist`.`name`, `bluerp_itemlist`.`price`, `bluerp_itemlist`.`type`
FROM bluerp_items
INNER JOIN bluerp_itemlist ON `bluerp_items`.`itemid` = `bluerp_itemlist`.`itemID`
ORDER BY `bluerp_items`.`quantity`  DESC";

$csq_items = mysqli_query($cs_link, $cs_items);             
while($csq_check = mysqli_fetch_array($csq_items)){

    $sid = $csq_check['steam_id'];
    $iid= $csq_check['itemid'];
    $itemname = $csq_check['name'];
    $quantity = $csq_check['quantity'];
    $type = $csq_check['type'];
    $price = $csq_check['price'];

    echo "<br>[ITEMS] Updating Item:" .$iid." ".$itemname." ".$quantity."";

    $mfdv_update = "INSERT INTO `cs_items` (`steam_id`, `item_id`, `itemname`, `quantity`, `type`, `price`)	VALUES ('$sid','$iid','$itemname','$quantity','$type','$price')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}
*/
//**********//
//  PART 2  //
//**********//

$mfdv_clear_inv = "TRUNCATE TABLE `cs_itemladder`;";
mysqli_query($mfdv_link, $mfdv_clear_inv);

$cs_inv = "SELECT `bluerp_items`.`steam_id`, `bluerp_items`.`itemid`, `bluerp_items`.`quantity`, `bluerp_itemlist`.`name`, `bluerp_itemlist`.`price`, `bluerp_itemlist`.`type`
FROM bluerp_items
INNER JOIN bluerp_itemlist ON `bluerp_items`.`itemid` = `bluerp_itemlist`.`itemID`
ORDER BY `bluerp_items`.`itemid`  ASC";

/*SELECT `cs_items`.`steam_id`, `cs_items`.`quantity`, `cs_items`.`price`
FROM cs_items
INNER JOIN cs_elo_primary ON `cs_items`.`steam_id` = `cs_elo_primary`.`steam_id`
ORDER BY `cs_items`.`steam_id`  DESC*/

$value=0;
$totalvalue=0;
$i=1;
$amnt=0;
$outamnt=0;
$outval=0;
$csq_inv = mysqli_query($cs_link, $cs_inv);             
while($csq_check = mysqli_fetch_array($csq_inv)){

    if($csq_check['itemid'] == $i){
    //Maths
    $amnt=$amnt+$csq_check['quantity'];
    $value=$amnt*$csq_check['price'];
    //Assign Name
    $name=$csq_check['name'];
    }else{
    //AssignID
    $iid = $csq_check['itemid'];

    //Out
    echo "<br>[INVENTORY] Updating Inventory Value: ".$csq_check['itemid']." - ".$csq_check['name']." - ".$amnt." - ".$value."";
    $mfdv_update = "INSERT INTO `cs_itemladder` (`itemid`, `value`, `quantity`,`name`)	VALUES ('$iid','$value','$amnt','$name')";
    mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));

    //Reset
    $amnt=0;
    $value=0;
    $i++;
    }
}


//******************************//
//END OF INVENTORY LADDERS
//******************************//
?>