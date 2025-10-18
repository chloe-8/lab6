<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventorymanagement";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){

    echo"Couldn't Connect Database!.";

}

?>