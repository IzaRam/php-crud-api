<?php

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

?>
