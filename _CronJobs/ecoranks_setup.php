<?php
//This updates every 24 hours, but does a little extra work to let users lookup their own rank in economy.
require ('csb_c.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//MFDV (Local Domain Database)
//CS (Remote Database)

$i=0;
$mfdv_clear_csh = "TRUNCATE TABLE `ranks_economy`;";
mysqli_query($mfdv_link, $mfdv_clear_csh);


$cs_bh = "SELECT * FROM `bluerp_players` ORDER BY `bank` DESC";
$cs_bhq = mysqli_query($cs_link, $cs_bh);


//$date2 = date('Y-m-d');
//$day_before = date( 'Y-m-d', strtotime( $date2 . ' -90 day' ) );
//echo "Deleting any existing rows with a date older than 90 days: ". $day_before;
//$mfdv_remove = "DELETE FROM `cs_banks_history` WHERE  `date` = $day_before;";
//mysqli_query($mfdv_link, $mfdv_remove) or die(mysqli_error($mfdv_link));

while($csq_check = mysqli_fetch_array($cs_bhq)){
    $i++;
    $rank = $i;
    $date = date('Y-m-d');
    $sid = $csq_check['steam_id'];  
    $cash = $csq_check['cash'];
    $bank = $csq_check['bank'];
    $income = $csq_check['income'];
    $exp = $csq_check['experience'];
    $res = $csq_check['respect'];
    $name = $csq_check['username'];

    $name = mysqli_real_escape_string($mfdv_link, $name);
    echo "Name:" .$name." Bank:" .$bank. " Cash:" .$cash." Rank:" .$rank."<br>";
$mfdv_update = "INSERT INTO `ranks_economy` (`date`, `steam_id`, `cash`, `bank`, `income`, `experience`, `respect`, `name`, `rank`) VALUES ('$date', '$sid', '$cash', '$bank', '$income', '$exp','$res','$name', '$rank')";
mysqli_query($mfdv_link, $mfdv_update) or die(mysqli_error($mfdv_link));
}
?>