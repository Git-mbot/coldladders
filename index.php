<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name=”viewport” content=”width=device-width, initial-scale=1″>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Cold Ladders</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="default.css" rel="stylesheet" type="text/css" media="all" />
<link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
</head>

<body onload="loader()" style="margin:0;">

<?php 
require ('csb_c.php');
require ('tables.php'); 

//Handling Steam ID Conversion
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
			return $id;
	}
}


//Economy
$first = microtime();
$mfdv_banks = "SELECT 	cs_banks_primary.steam_id, cs_banks_primary.cash, cs_banks_primary.bank, cs_banks_primary.income, 
						cs_banks_secondary.cash, cs_banks_secondary.bank,cs_banks_secondary.income, cs_banks_primary.name 
				FROM cs_banks_primary 
				INNER JOIN cs_banks_secondary 
				ON cs_banks_primary.steam_id=cs_banks_secondary.steam_id 
				ORDER BY cs_banks_primary.bank DESC LIMIT 25";
$mfdvq_banks = mysqli_query($mfdv_link, $mfdv_banks);
//Get Top User and Reset for Table
while($mfdv_row = mysqli_fetch_array($mfdvq_banks)){
	$topmoney = $mfdv_row[7];
	break;
	}
mysqli_data_seek( $mfdvq_banks, 0 );

//Combat
$mfdv_elo = "SELECT cs_elo_primary.steam_id, cs_elo_primary.elo, cs_elo_primary.kills, cs_elo_primary.deaths, 
					cs_elo_secondary.elo, cs_elo_secondary.kills,cs_elo_secondary.deaths, cs_elo_primary.name 
			FROM cs_elo_primary INNER JOIN cs_elo_secondary 
			ON cs_elo_primary.steam_id=cs_elo_secondary.steam_id 
			ORDER BY cs_elo_primary.elo DESC LIMIT 25";
$mfdvq_elo = mysqli_query($mfdv_link, $mfdv_elo);
//Get Top User and Reset for Table
while($mfdv_row = mysqli_fetch_array($mfdvq_elo)){
	$topelo = $mfdv_row['name'];
	break;
}
mysqli_data_seek( $mfdvq_elo, 0 );

//Combat
$mfdv_kills = "SELECT * FROM cs_elo_primary ORDER BY cs_elo_primary.kills DESC LIMIT 25";
$mfdvq_kills = mysqli_query($mfdv_link, $mfdv_kills);
//Get Top User and Reset for Table
while($mfdv_row = mysqli_fetch_array($mfdvq_kills)){
	$topkills = $mfdv_row['name'];
	break;
}
mysqli_data_seek( $mfdvq_kills, 0 );


//Respect
$mfdv_respect = "SELECT * FROM `cs_expresp_ladder` WHERE `respect` < '500000' ORDER BY `respect` DESC";
$mfdvq_respect = mysqli_query($mfdv_link, $mfdv_respect);
while($mfdv_row = mysqli_fetch_array($mfdvq_respect)){
	$toprespect = $mfdv_row['name'];
	break;
}
mysqli_data_seek( $mfdvq_respect, 0 );


//Experience
$mfdv_experience = "SELECT * FROM `cs_expresp_ladder` WHERE `experience` < '500000' ORDER BY `experience` DESC";
$mfdvq_experience = mysqli_query($mfdv_link, $mfdv_experience);
while($mfdv_row = mysqli_fetch_array($mfdvq_experience)){
	$topexperience = $mfdv_row['name'];
	break;
}
mysqli_data_seek( $mfdvq_experience, 0 );

//Playtime
$mfdv_playtime = "SELECT * FROM `cs_banks_primary` ORDER BY `cs_banks_primary`.`minutes` DESC;";
$mfdvq_playtime = mysqli_query($mfdv_link, $mfdv_playtime);
while($mfdv_row = mysqli_fetch_array($mfdvq_playtime)){
	if($mfdv_row['steam_id']!="STEAM_0:1:25306470"){
		$days=0;
		$hours=0;
		$minutes=0;
		$totalplaytime+=$mfdv_row['minutes'];
	
		if($mfdv_row['minutes'] < 60){
			$minutes = $mfdv_row['minutes'];
		}elseif($mfdv_row['minutes'] < 1440){
			$hours=$mfdv_row['minutes']/60;
			$minutes=$mfdv_row['minutes']%60;
		}elseif($mfdv_row['minutes'] >= 1440) {
			$days=$mfdv_row['minutes']/1440;
			$days_remainder=$mfdv_row['minutes']%1440;
			$hours=$days_remainder/60;
			$minutes=$days_remainder%60;
		}
	
		$days=number_format($days,0);
		$hours=number_format($hours,0);
		$minutes=number_format($minutes,0);

	$topplaytime = $mfdv_row['name'];
	break;
	}
}
mysqli_data_seek( $mfdvq_playtime, 0 );

//Items Standard
$mfdv_items = "SELECT * FROM `cs_itemladder` WHERE `itemid` != '251' AND `itemid` != '259' AND `itemid` != '255' AND `quantity` > '0' ORDER BY `value` DESC";
$mfdvq_items = mysqli_query($mfdv_link, $mfdv_items);
while($mfdv_row = mysqli_fetch_array($mfdvq_items)){
	$topitemvaluename = $mfdv_row['name'];
	$topitemvalue = number_format($mfdv_row['value']);
	break;
}
mysqli_data_seek( $mfdvq_items, 0 );

//Items for Ladder Leader
$mfdv_items_top = "SELECT * FROM `cs_itemladder` WHERE `itemid` != '251' AND `itemid` != '259' AND `itemid` != '255' AND `quantity` > '0' ORDER BY `quantity` DESC";
$mfdvq_items_top = mysqli_query($mfdv_link, $mfdv_items_top);
while($mfdv_row = mysqli_fetch_array($mfdvq_items_top)){
	$topitemname = $mfdv_row['name'];
	$topitemcnt = number_format($mfdv_row['quantity']);
	break;
}
mysqli_data_seek( $mfdvq_items_top, 0 );


//Elo Season
$mfdv_eloseason = "SELECT * FROM `cs_elo_seasons` WHERE `season`='0' ORDER BY `elo` DESC LIMIT 25";
$mfdvq_eloseason = mysqli_query($mfdv_link, $mfdv_eloseason);
?>

<div id="loader"></div>

<div style="display:none;" id="animate" class="animate-bottom">

<div id="header-wrapper">
	<div id="header" class="container">
		<div id="logo">
			<h1><a href="#">Cold Ladders</a></h1>
			<span>Created By <a href="https://steamcommunity.com/id/micobot" rel="nofollow">MicoBOT</a> | <a href="https://mfdv.ca/m/" rel="nofollow">Open</a> Mobile Friendly</span> 
		</div>
		<div id="menu">
			<ul>
				<li><a onclick="EcoModFnc();">Economy</a></li>
				<li><a onclick="ComModFnc();">Combat</a></li>
				<li><a onclick="PlaytimeModFnc();">Playtime</a></li>
				<li><a onclick="ResModFnc();">Respect</a></li>
				<li><a onclick="ExpModFnc();">Experience</a></li>
				<li><a onclick="ItemModFnc();">Items</a></li>
			</ul>
		</div>
	</div>
</div>


<div id="wrapper3">
	<div id="portfolio" class="container">
	<div class="title">
		<p style="color: #f0f0f0;">Looking for a new Steam profile picture? Check out <a target="_blank" href="http://www.avatararchive.ca/" style="color: #64cc50;text-decoration: none;">AvatarArchive.ca</a></p>
	</div>
		<div class="title">
			<h2>Ladder Leaders</h2>
		</div>

		<div class="pbox1">
			<div class="column1">
				<div class="box">	<h3><i class="fa fa-money fa-4x" aria-hidden="true"></i></h3>
					<h3>Largest Bank</h3>
					<p><?php echo $topmoney; ?> has the most money banked through money-making tasks and schemes.</p>
				</div>
			</div>

			<div class="column2">
				<div class="box"> <h3><i class="fa fa-trophy fa-4x" aria-hidden="true"></i></h3>
					<h3>Best Fighter</h3>
					<p><?php echo $topelo; ?> has the highest ELO rating in the Combat Ladder.</p>
				</div>
			</div>

			<div class="column3">
				<div class="box"> <h3><i class="fa fa-th-list fa-4x" aria-hidden="true"></i></h3>
					<h3>Most Dedicated Cop</h3>
					<p><?php echo $topexperience; ?> has the most cop experience points.</p>
				</div>
			</div>

			<div class="column4">
				<div class="box"> <h3><i class="fa fa-hourglass-2 fa-4x" aria-hidden="true"></i></h3>
					<h3>Most Playtime</h3>
					<p><?php echo $topplaytime; ?> has absolutely no life with <?php echo  $days ." days ".$hours." hours ". $minutes." minutes played.\n";?></p>
				</div>
			</div>
		</div>

		<div class="pbox2">
			<div class="column1">
				<div class="box">	<h3><i class="fa fa-dropbox fa-4x" aria-hidden="true"></i></h3>
					<h3>Most Hoarded Item</h3>
					<p><?php echo $topitemname; ?> is the most popular item with over <?php echo $topitemcnt; ?> in circulation!</p>
				</div>
			</div>

			<div class="column2">
				<div class="box"> <h3><i class="fa fa-dropbox fa-4x" aria-hidden="true"></i></h3>
					<h3>Most Valued Item</h3>
					<p><?php echo $topitemvaluename; ?> is the most valuable item with over $<?php echo $topitemvalue; ?> worth in circulation!</p>
				</div>
			</div>

			<div class="column3">
				<div class="box"> <h3><i class="fa fa-th-list fa-4x" aria-hidden="true"></i></h3>
					<h3>Most Respected Rebel</h3>
					<p><?php echo $toprespect; ?> has the most rebel respect points.</p>
				</div>
			</div>

			<div class="column4">
				<div class="box"> <h3><i class="fa fa-ambulance fa-4x" aria-hidden="true"></i></h3>
					<h3>Deadliest Player</h3>
					<p><?php echo $topkills; ?> has most kills in the Combat Ladder.</p>
				</div>
			</div>
		</div>

	</div>
</div>

<style>
input[type=text], select {
  width: 300px;
  padding: 13px 20px;
  margin: 8px 0;
  display: inline-block;
  border: none;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100px;
  background-color: rgba(91, 217, 255, 0.8);
  color: white;
  display:inline-block;
  padding: 13px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
}


#wrapper3 .theform {
  padding: 20px;
  width: 600px;
  text-align: center;
  margin: auto;
}

#wrapper3 .theform p {
	text-align: center;
	font-size: 1.5em;
	color: rgba(91, 217, 255, 0.8);
}

#wrapper3 .theform h3 {
	font-size: 2em;
}
</style>
<div id="wrapper3">
	<div id="portfolio" class="container">
		<div class="title">
			<h2>What is my rank?</h2>
			<h3>Find out your economy rank!</h3>
		</div>
		<div class="theform">
			<form action="index.php">
			<input type="submit" value="Submit"><input type="text" name="steamid" placeholder="Example: STEAM_0:0:24352559" value="">
			</form>
		<?php
		if($_GET["steamid"] == null ){ 

		}else{
		$id=$_GET["steamid"];

		$id2 = mysqli_real_escape_string($mfdv_link, $id);

		$getrank = "SELECT * FROM `ranks_economy` WHERE `steam_id` = '$id2'";
		$getrankQ = mysqli_query($mfdv_link, $getrank);
		$getrank = mysqli_fetch_array($getrankQ);

		$y = $getrankQ;
		$x = mysqli_num_rows($y);

			if($x == 0){
				echo "<p>SteamID not found or input invalid.</p>\n";
			}else{
				$bank = number_format($getrank['bank']);
				echo "<p>User Found: ".$getrank['name'].", rank #".$getrank['rank']. " with $".$bank." banked.</p>\n";
			}
		}
		?>
		</div>
	</div>
</div>


<div id="wrapper3">
<div id="portfolio" class="container">
		<div class="title">
			<h2>Combat Season 0</h2>
			<h3>Final Standings Top 25</h3>
		</div>
	</div>
	<div class="container">
		<div class="table-wrapper">
				<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
					<thead>
						<tr>
							<th><b>Rank</b></th>
							<th><b>Name</b></th>
							<th><b>SteamID</b></th>
							<th><b>Elo</b></th>
							<th><b>Kills</b></th>
							<th><b>Deaths</b></th>
							<th><b>K/D Ratio</b></th>
						</tr>
					</thead>

						<tbody>
				
							<?php
							tableSeason($mfdvq_eloseason);
							?>

						</tbody>

				</table>
		</div>
	</div>
</div>

<div id="EcoMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="EcoModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Eco">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Rank</b></th>
												<th><b>Name</b></th>
												<th><b>SteamID</b></th>
												<th><b>Income</b></th>
												<th><b>Bank</b></th>
												<th><b>Money</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tableEconomy($mfdvq_banks);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>

<div id="ComMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="ComModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Com">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Rank</b></th>
												<th><b>Name</b></th>
												<th><b>SteamID</b></th>
												<th><b>Elo</b></th>
												<th><b>Kills</b></th>
												<th><b>Deaths</b></th>
												<th><b>KDR</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tableCombat($mfdvq_elo);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>

<div id="ResMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="ResModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Res">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Rank</b></th>
												<th><b>Name</b></th>
												<th><b>SteamID</b></th>
												<th><b>Respect</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tableRespect($mfdvq_respect);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>

<div id="ExpMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="ExpModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Exp">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Rank</b></th>
												<th><b>Name</b></th>
												<th><b>SteamID</b></th>
												<th><b>Experience</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tableExperience($mfdvq_experience);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>

<div id="PlaytimeMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="PlaytimeModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Playtime">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Rank</b></th>
												<th><b>Name</b></th>
												<th><b>SteamID</b></th>
												<th><b>Playtime</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tablePlaytime($mfdvq_playtime);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>


<div id="ItemMod" class="modal">
	<div class="modal-content">
		<span class="close"><a onclick="ItemModClose();">Close Ladder</a></span>
			<div class="modal-body">
				<div id="Item">
					<div id="wrapper3">
						<div class="container">
							<div class="table-wrapper">
									<table cellspacing="0" class="xtable" style="width:100%;margin: 0px auto;font-size:16px;">
										<thead>
											<tr>
												<th><b>Item</b></th>
												<th><b>Quantity</b></th>
												<th><b>Price</b></th>
												<th><b>Total Value</b></th>
											</tr>
										</thead>

											<tbody>
									
												<?php
												tableItems($mfdvq_items);
												?>

											</tbody>

									</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="modal-footer">
		<br>
		<br>
		<br>
    </div>
</div>

<div id="copyright">
	<p>&copy; MFDV.CA 2019. All rights reserved.</p><br>
			
	<p> <?php 
	$second=microtime();
	$seconds = $second - $first + 1;
	$secondss = number_format($seconds,3);
echo "Page generated in " . $secondss . "ms<br>"; ?> </p>
</div>


<!-- Loader End -->
</div>

<script>
	var myVar;
	
	function loader() {
	  myVar = setTimeout(showPage, 1000);
	}
	
	function showPage() {
	  document.getElementById("loader").style.display = 'none';
	  document.getElementById("animate").style.display = 'block';
	}
</script>

<script>

function EcoModFnc() {EcoMod.style.display = "block";}
function EcoModClose() {EcoMod.style.display = "none";}

function ComModFnc() {ComMod.style.display = "block";}
function ComModClose() {ComMod.style.display = "none";}

function ResModFnc() {ResMod.style.display = "block";}
function ResModClose() {ResMod.style.display = "none";}

function ExpModFnc() {ExpMod.style.display = "block";}
function ExpModClose() {ExpMod.style.display = "none";}

function PlaytimeModFnc() {PlaytimeMod.style.display = "block";}
function PlaytimeModClose() {PlaytimeMod.style.display = "none";}

function ItemModFnc() {ItemMod.style.display = "block";}
function ItemModClose() {ItemMod.style.display = "none";}

</script>

</body>
</html>
