<?php
try{
define("ImageUrl", "https://www.address-base.de/images/product_images/thumbnail_images/x10_4.jpg.pagespeed.ic.nJKZ8sR9Tm.jpg");
define("path", "../files/");
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");



$getAtricleInfoStmnt = $pdo->prepare("SELECT * FROM articles");
$getArticleInfoResult = $getAtricleInfoStmnt->execute();

while ($getArticleInfo = $getAtricleInfoStmnt->fetch(PDO::FETCH_ASSOC)){
	$writeImageStmnt = $pdo->prepare("INSERT INTO images (orderNumber, mainNumber, imageUrl) VALUES (:ordernumber, :mainNumber, :imageUrl)");
	$writeImageResult = $writeImageStmnt->execute(array("ordernumber" => $getArticleInfo['ordernumber'], "mainNumber" => $getArticleInfo['ordernumber'], "imageUrl" => ImageUrl));
	}

if(!isset($_SESSION['iheaders'])){
	$gethead_stmnt = $pdo->prepare("SELECT * FROM images LIMIT 0,1");
	$gethead_res = $gethead_stmnt->execute();
	$gethead = $gethead_stmnt->fetch(PDO::FETCH_ASSOC);
	$_SESSION['iheaders'] = array_keys($gethead);
}

$getImageStmnt = $pdo->prepare("SELECT * FROM images");
$getImageResult = $getImageStmnt->execute();

$images = null;
$images = array();
while ($getImage = $getImageStmnt->fetch(PDO::FETCH_ASSOC)){
	array_push($images, array_values($getImage));
}

$file = fopen(path."images.csv", "w");
fputcsv($file, $_SESSION['iheaders'], ";", '"');
foreach ($images as $image){
	fputcsv($file, $image, ";", '"');
}
fclose($file);
}catch (PDOException $pdoE){
	echo $pdoE->getMessage();
}