<?php

function getRecipeIngredientes() {

	require "config.php";

	$id = ($_GET['id'] != null && $_GET['id'] > 0 ) ? mysqli_real_escape_string($conn, (int)$_GET['id']) : false;

	if (!$id) {
		echo "Parameter ID invalid!";
		return http_response_code(400);
	}

	$sql = "SELECT ingrediente.* FROM ingrediente INNER JOIN receita_ingrediente ON receita_ingrediente.ingrediente_id = ingrediente.id WHERE receita_ingrediente.receita_id = {$id}";  

	$result = mysqli_query($conn, $sql);

	if ($result === false) {
		echo mysqli_error();
		return http_response_code(500);
	}

	$ingredientes = [];
	$i = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$ingredientes[$i]['id'] = $row['id'];
		$ingredientes[$i]['nome'] = $row['nome'];
		$i++;
	}

	echo json_encode($ingredientes);

	mysqli_free_result($result);
	mysqli_close($conn);
}

function getIngredientes() {
	
	require 'config.php';

	$sql = "SELECT * FROM ingrediente";

	$result = mysqli_query($conn, $sql);

	if ($result === false) {
		echo mysqli_error();
		return http_response_code(500);
	}

	$ingredientes = [];
	$i = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$ingredientes[$i]['id'] = $row['id'];
		$ingredientes[$i]['nome'] = $row['nome'];
		$i++;
	}

	echo json_encode($ingredientes);
	mysqli_free_result($result);
	mysqli_close($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['id'] ==! null) {
	getRecipeIngredientes();
} else {
	getIngredientes();
}

?>


