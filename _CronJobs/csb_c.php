<?php
//remote database we pull from to local
$cs_link = mysqli_connect("host", "user", "pass", "db");
if($cs_link === false){
    echo "There was an error connecting to REMOTE. <br>Error: ";
    die(mysqli_connect_error());
}
//local database we send data to.
$mfdv_link = mysqli_connect("localhost", "use", "pass", "db");
if($mfdv_link === false){
    echo "There was an error connecting to LOCAL. <br>Error: ";
    die(mysqli_connect_error());
}

?>
