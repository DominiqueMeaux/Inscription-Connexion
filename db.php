<?php

session_start();

try{

	$db = new PDO('mysql:hos=localhost;dbname=', 'root', '', [ // Mettre le nom d'une base de données et mot de passe

		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

		PDO::ATTR_PERSISTENT => true, 

		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,

	]);
}
catch(Exception $e){
	die('Erreur : '.$e->getMessage());
}