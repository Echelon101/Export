<?php
session_start();

define("CatOffset", 4);
define("CatParent", 5);
define("ROOT_PARENT", 3);

$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");
/*
$categories = array('Branchen', 'Alle Branchen', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K' , 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$count = 5;
foreach ($categories as $category){
	$newCatS = $pdo->prepare("INSERT INTO categories (categoryId, parentID, description, active) VALUES (:categoryId, :parentID, :description, :active)");
	$newCatR = $newCatS->execute(
			array(
				"categoryId" => $count,
				"parentID" => CatParent,
				"description" => $category,
				"active" => true,
			)
		);
	$count++;
}

*/

$i = 5;

$standardCategoreies = array('Alle Branchen', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K' , 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$rootCategories = array('Deutschland', 'Österreich', 'Schweiz');

foreach ($rootCategories as $rootCategory){
	$create_category_stmnt = $pdo->prepare("INSERT INTO newcategories (categoryId, parentID, description, active) VALUES (:categoryId, :parentID, :description, :active)");
	$create_category_res = $create_category_stmnt->execute(array(
			"categoryId" => $is,
			"parentID" => $id,
			"description" => $category,
			"active" => true,
			)
		);
	
}