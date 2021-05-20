<?php

function getIngredientes() {
		
	require "config.php";

	$sql = "SELECT * FROM despensa";

	$result = mysqli_query($conn, $sql);

	if ($result === false) {
		echo "Erro ao obter ingredientes: " . mysqli_error();
		return http_response_code(500);
	}

	$ingredientes = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($result)) {

		$ingrediente_id = (int)$row['ingrediente_id'];
		$sqlIng = "SELECT * FROM ingrediente WHERE id={$ingrediente_id}"; 
		$resultIng = mysqli_query($conn, $sqlIng);
		if ($result === false) {
			echo mysqli_error($conn);
			return http_response_code(500);
		}
		$rowIng = mysqli_fetch_assoc($resultIng);
		$ingredientes[$i]['nome'] = $rowIng['nome'];

		$ingredientes[$i]['id'] = $row['id'];
		$ingredientes[$i]['quantidade'] = $row['quantidade'];
		$i++;

		mysqli_free_result($resultIng);
	}

	echo json_encode($ingredientes);

	mysqli_free_result($result);
	mysqli_close($conn);
}

function saveIngrediente() {

	require "config.php";
	
	$dataPost = file_get_contents("php://input");

	if (isset($dataPost) && !empty($dataPost)) {
		
		$request = json_decode($dataPost);

		if (trim($request->nome) == "" ||(int)$request->quantidade <= 0) {
			echo "Dados inválidos!";
			return http_response_code(400);
		}

		$nome = mysqli_real_escape_string($conn, trim($request->nome));
		$quantidade = mysqli_real_escape_string($conn, (float)$request->quantidade);
		$ingrediente_id = 0;

		$sqlIng = "SELECT id FROM ingrediente WHERE nome='{$nome}'";
		$resultIng = mysqli_query($conn, $sqlIng);
		if (mysqli_num_rows($resultIng) > 0 ) {
			$rowIng = mysqli_fetch_assoc($resultIng);
			$ingrediente_id = (int)$rowIng['id'];
		} else {
			$sqlIng = "INSERT INTO ingrediente (nome) VALUES ('{$nome}')";
			mysqli_query($conn, $sqlIng);
			$ingrediente_id = (int)mysqli_insert_id($conn);
		}
		mysqli_free_result($resultIng);

		$sql = "INSERT INTO despensa(ingrediente_id, quantidade) VALUES ('{$ingrediente_id}', '{$quantidade}')";

		if (mysqli_query($conn, $sql)) {
			echo "Ingrediente adicionado com sucesso!";
			return http_response_code(201);
		} else {
			$error = mysqli_error($conn);
			if (strpos($error, "Duplicate") === 0) {
				echo "Ingrediente já adicionado!";
				return http_response_code(409);
			}
			echo "Erro ao salvar ingrediente: " . $error;
			return http_response_code(500);
		}
	} else {
		echo "Dados inválidos!";
		return http_response_code(400);
	}
	mysqli_close($conn);
}

function deleteIngrediente() {

	require "config.php";

	$id = ($_GET['id'] != null && $_GET['id'] > 0 ) ? mysqli_real_escape_string($conn, (int)$_GET['id']) : false;

	if (!$id) {
		echo "Parameter ID invalid!";
		return http_response_code(400);
	}

	$sql = "DELETE FROM despensa WHERE id={$id} LIMIT 1";

	if(mysqli_query($conn, $sql)) {
		if (mysqli_affected_rows($conn) > 0) {
			echo "Ingrediente deletado da despensa com sucesso!";
			return http_response_code(200);
		} else {
			echo "Nenhum indrediente encontrado com esse id";
			return http_response_code(404);
		}
	} else {
		echo "Erro ao deletar ingrediente da despensa!";
		return http_response_code(500);
	}

	mysqli_close($conn);

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	getIngredientes();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	saveIngrediente();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && $_GET['id'] ==! null) {
	deleteIngrediente();
}

if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

	exit(0);
}

?>
