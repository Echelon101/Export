<?php
session_start();
define("path", "../files/");

$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");

if(!isset($_SESSION['cheaders'])){
	$gethead_stmnt = $pdo->prepare("SELECT * FROM categories LIMIT 0,1");
	$gethead_res = $gethead_stmnt->execute();
	$gethead = $gethead_stmnt->fetch(PDO::FETCH_ASSOC);
	$_SESSION['cheaders'] = array_keys($gethead);
}


$exportS = $pdo->prepare("SELECT * FROM newcategories");
$exportR = $exportS->execute();

$save = null;
$save = array();
$file = fopen(path . "categories.csv", "w");


while ($export = $exportS->fetch(PDO::FETCH_ASSOC)){
	array_push($save, array_values($export));
}


fputcsv($file, $_SESSION['cheaders'], ";", '"');

foreach ($save as $category){
	fputcsv($file, $category, ";", '"');
}
fclose($file);