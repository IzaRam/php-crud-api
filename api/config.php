<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json");

define("DB_HOST","localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "diario");

function connect() {
	$connect  = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

	if ($connect === false) {
		die ("Failed to connect: " . mysqli_connect_error());
	}

	mysqli_set_charset($connect, "utf8");

	return $connect;
}

$conn = connect();

?>
