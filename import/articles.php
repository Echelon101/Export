<?php
session_start();
try{
//Constants
define("path", "../files/");
define("Article_Prefix", "AD");
define("tax", "19.00");
define("supplier", "MaxxDiscount GmbH & Co. KG");
define("ArtImage", "https://www.address-base.de/images/product_images/info_images/x10_4.jpg.pagespeed.ic._XWEulng58.jpg");
define("CatAlleBranchen", 6);

$headers = 'SELECT * FROM articles LIMIT 0,1';

$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8","root","");

$get_Info = $pdo->prepare("SELECT `ID`, `Name`, `rowcount` FROM rowcount");
$get_InfoResult = $get_Info->execute();
$saveset = null;
$saveset = array();

while($get_InfoSave = $get_Info->fetch(PDO::FETCH_ASSOC)){
	//array_push($saveset, array_values($get_InfoSave));
	
	$artNr = Article_Prefix . str_pad($get_InfoSave['ID'], 5,"0", STR_PAD_LEFT);
	$ek = ceil($get_InfoSave['rowcount']/5);
	
	$description = "Branche: " . $get_InfoSave['Name'] . ", Datensätze: " . $get_InfoSave['rowcount'];
	$descriptionLong = "Branche: " . $get_InfoSave['Name'] . ", Datensätze: " . $get_InfoSave['rowcount'];
	
	$createArticle = $pdo->prepare("INSERT INTO articles (ordernumber, mainnumber, name, supplier, tax, price_EK, imageUrl, active, description, description_long, categories) VALUES (:ordernumber, :mainnumber, :name, :supplier, :tax, :price_EK, :imageUrl, :active, :description, :description_long, :categories)");
	$createArticleResult = $createArticle->execute(
			array(
					"ordernumber" => $artNr,
					"mainnumber" => $artNr,
					"name" => $get_InfoSave['Name'],
					"supplier" => supplier,
					"tax" => tax,
					"price_EK" => $ek,
					"imageUrl" => ArtImage,
					"active" => true,
					"description" => $description,
					"description_long" => $descriptionLong,
					//"categories" => $category
			)
		);
	$getFilterdArticle = $pdo->prepare("SELECT * FROM rowcountbund WHERE Name = :Name");
	$getFilterdArticleR = $getFilterdArticle->execute(array('Name' => $get_InfoSave['Name']));
	
	while($getFilterdArticleS = $getFilterdArticle->fetch(PDO::FETCH_ASSOC)){
		$createFilterdArticle = $pdo->prepare("INSERT INTO articles (ordernumber, mainnumber, name, supplier, tax, price_EK, imageUrl, active, description, description_long, categories) VALUES (:ordernumber, :mainnumber, :name, :supplier, :tax, :price_EK, :imageUrl, :active, :description, :description_long, :categories)");
		$createFilterdArticleResult = $createFilterdArticle->execute(
				array(
						"ordernumber" => $artNr,
						"mainnumber" => $artNr,
						"name" => $get_InfoSave['Name'],
						"supplier" => supplier,
						"tax" => tax,
						"price_EK" => $ek,
						"imageUrl" => ArtImage,
						"active" => true,
						"description" => $description,
						"description_long" => $descriptionLong,
						//"categories" => $category
				)
				);
	}
}
}catch (PDOException $pdoE){
	echo $pdoE->getMessage();
}


if(!isset($_SESSION['theaders'])){
	$theaders_stmnt = $pdo->prepare($headers);
	$theaders_result = $theaders_stmnt->execute();
	$theaders = $theaders_stmnt->fetch(PDO::FETCH_ASSOC);
	$_SESSION['theaders'] = array_keys($theaders);
}  
$doExport = $pdo->prepare("SELECT * FROM articles");
$doExportR = $doExport->execute();

$export = null;
$export = array();
while ($doExportS = $doExport->fetch(PDO::FETCH_ASSOC)){
	array_push($export, array_values($doExportS));
}
$file = fopen(path."export.txt", "w");
fputcsv($file, $_SESSION['theaders'], ";", '"');
foreach ($export as $entry){
	fputcsv($file, $entry, ";", '"');
}
fclose($file);