<?php

define("HOST", "fdb22.awardspace.net"); 
define("USER", "2838970_bancodedados");
define("PASSWORD", "trabalhodepi123"); 
define("DATABASE", "2838970_bancodedados");

function conectaAoMySQL()
{
	$conn = new mysqli(HOST, USER, PASSWORD, DATABASE);
	if ($conn->connect_error)
	throw new Exception('Falha na conexão com o MySQL: ' . $conn->connect_error);

	return $conn;   
}

?>