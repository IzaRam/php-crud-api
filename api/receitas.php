<?php

function getRecipes() {
		
	require "config.php";

	$sql = "SELECT * FROM receita";

	$result = mysqli_query($conn, $sql);

	if ($result === false) {
		echo mysqli_error($conn);
		return http_response_code(500);
	}

	$receitas = [];
	$i = 0;
	while($row = mysqli_fetch_assoc($result)) {
		$receitas[$i]['id'] = $row['id'];
		$receitas[$i]['nome'] = $row['nome'];
		$receitas[$i]['image_url'] = $row['image_url'];
		$receitas[$i]['descricao'] = $row['descricao'];
		$i++;
	}

	echo json_encode($receitas);

	mysqli_free_result($result);
	mysqli_close($conn);
}

function saveRecipe() {

	require "config.php";
	
	$dataPost = file_get_contents("php://input");

	if (isset($dataPost) && !empty($dataPost)) {
		
		$request = json_decode($dataPost);

		if (trim($request->nome) == "" || trim($request->imageUrl) == "" || trim($request->descricao) == "") {
			echo "Dados inválidos!";
			return http_response_code(400);
		}

		$nome = mysqli_real_escape_string($conn, trim($request->nome));
		$imageUrl = mysqli_real_escape_string($conn, trim($request->imageUrl));
		$descricao = mysqli_real_escape_string($conn, trim($request->descricao));

		$sql = "INSERT INTO receita(nome, image_url, descricao) VALUES ('{$nome}', '{$imageUrl}', '{$descricao}')";

		if (mysqli_query($conn, $sql)) {
			echo "Receita adicionada com sucesso!";
			return http_response_code(201);
		} else {
			echo "Erro ao salvar receita: " . mysqli_error($conn);
			return http_response_code(500);
		}
	} else {
		echo "Dados inválidos!";
		return http_response_code(400);
	}

	mysqli_close($conn);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	getRecipes();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	saveRecipe();
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
