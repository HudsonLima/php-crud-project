<?php

//$conn = 'mysql:host=mysql.hostinger.com.br;dbname=u149456507_dm104';

$conn = 'mysql:host=localhost;dbname=u149456507_dm104';

try {
	$db = new PDO($conn, 'root', 'clara02');

	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	if($e->getCode() == 1049){
		echo "Banco de dados errado.";
	}else{
		echo $e->getMessage();
	}
}