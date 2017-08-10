<?php
session_start();

define("CatOffset", 4);
define("CatParent", 5);

$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");

$categories = array('Branchen', 'Alle Branchen', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K' , 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$count = 4;
foreach ($categories as $category){
	$count++;
	
	$newCatS = $pdo->prepare("INSERT INTO categories (categoryId, parentID, description, active) VALUES (:categoryId, :parentID, :description, :active)");
	$newCatR = $newCatS->execute(
			array(
				"categoryId" => $count,
				"parentID" => CatParent,
				"description" => $category,
				"active" => true,
			)
		);
}
