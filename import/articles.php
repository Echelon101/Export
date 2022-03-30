$<?php
session_start();
try{
//Constants
define("path", "../files/");
define("Article_Prefix", "AS");
define("tax", "19.00");
define("supplier", "Portal Service GmbH & Co. KG");
define("ArtImage", "http://www.addressscout24.de/media/image/9d/1a/8c/addressbox01_large.png");
define("CatAlleBranchen", 6);

$headers = 'SELECT * FROM articles LIMIT 0,1';

$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8","root","");

$get_Info = $pdo->prepare("SELECT `ID`, `Name`, `rowcount` FROM rowcount");
$get_InfoResult = $get_Info->execute();
$saveset = null;
$saveset = array();
$category = 6;
$count = 1;
while($get_InfoSave = $get_Info->fetch(PDO::FETCH_ASSOC)){
	//array_push($saveset, array_values($get_InfoSave));
	$name = strtoupper($get_InfoSave['Name']);
	$artNr = Article_Prefix . str_pad($count, 5,"0", STR_PAD_LEFT);
	$ek = ceil($get_InfoSave['rowcount']/5);
	switch (mb_substr($name, 0, 1)){
		case "Ä":
			$categorySpec = "|7";
			break;
		case "A":
			$categorySpec = "|7";
			break;
		case "B":
			$categorySpec = "|8";
			break;
		case "C":
			$categorySpec = "|9";
			break;
		case "D":
			$categorySpec = "|10";
			break;
		case "E":
			$categorySpec = "|11";
			break;
		case "F":
			$categorySpec = "|12";
			break;
		case "G":
			$categorySpec = "|13";
			break;
		case "H":
			$categorySpec = "|14";
			break;
		case "I":
			$categorySpec = "|15";
			break;
		case "J":
			$categorySpec = "|16";
			break;
		case "K":
			$categorySpec = "|17";
			break;
		case "L":
			$categorySpec = "|18";
			break;
		case "M":
			$categorySpec = "|19";
			break;
		case "N":
			$categorySpec = "|20";
			break;
		case "Ö":
			$categorySpec = "|21";
			break;
		case "O":
			$categorySpec = "|21";
			break;
		case "P":
			$categorySpec = "|22";
			break;
		case "Q":
			$categorySpec = "|23";
			break;
		case "R":
			$categorySpec = "|24";
			break;
		case "S":
			$categorySpec = "|25";
			break;
		case "T":
			$categorySpec = "|26";
			break;
		case "Ü":
			$categorySpec = "|27";
			break;
		case "U":
			$categorySpec = "|27";
			break;
		case "V":
			$categorySpec = "|28";
			break;
		case "W":
			$categorySpec = "|29";
			break;
		case "X":
			$categorySpec = "|30";
			break;
		case "Y":
			$categorySpec = "|31";
			break;
		case "Z":
			$categorySpec = "|32";
			break;
		default:
			$categorySpec = NULL;
			echo mb_substr($name, 0, 1);
			echo $artNr;
			break;
	}
	switch ($artNr){
		case "AS00108":
			$categorySpec = "|27";
			echo "fixed Ü";
			break;
		case "AS00109":
			$categorySpec = "|27";
			echo "fixed Ü";
			break;
		default:
			break;
	}
	$descriptionLong= "<h2>" . $get_InfoSave['Name'] ." kaufen</h2>
	<p><br />Sie erhalten " . $get_InfoSave['rowcount'] . " Adressen der gew&auml;hlten Branche als kommagetrennte Textdatei. Diese k&ouml;nnen Sie in den allermeisten F&auml;llen problemlos in Ihre Anwendungsprogramme importieren und verwenden. Aus Platz- und Performancegr&uuml;nden erhalten Sie diese in gepackter Form (ZIP-Datei).<br /><br />Zur Neukundengewinnung ideal auch inkl. E-Mail-Adressen, sofern bekannt und angegeben.<br />Steigern Sie Ihren Umsatz durch unsere gepr&uuml;ften Branchenadressen.<br /><br /></p>";
	$description = "Branche: " . $get_InfoSave['Name'] . ", Datensätze: " . $get_InfoSave['rowcount'];
	
	$createArticle = $pdo->prepare("INSERT INTO articles (ordernumber, mainnumber, name, supplier, tax, price_EK, imageUrl, active, main, description, description_long, categories) VALUES (:ordernumber, :mainnumber, :name, :supplier, :tax, :price_EK, :imageUrl, :active, :main, :description, :description_long, :categories)");
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
					"main" => true,
					"description" => $description,
					"description_long" => $descriptionLong,
					"categories" => $category.$categorySpec
			)
		);
	$getFilterdArticle = $pdo->prepare("SELECT * FROM rowcountbund WHERE Name = :Name");
	$getFilterdArticleR = $getFilterdArticle->execute(array('Name' => $get_InfoSave['Name']));
	$count++;
	while($getFilterdArticleS = $getFilterdArticle->fetch(PDO::FETCH_ASSOC)){
		$artNrF = Article_Prefix . str_pad($count, 5,"0", STR_PAD_LEFT);
		$ekf = ceil($getFilterdArticleS['rowcount']/5);
		
		$nameF = strtoupper($getFilterdArticleS['Name']);
		
		switch (mb_substr($nameF, 0, 1)){
			case "Ä":
				$categorySpecF = "|7";
				break;
			case "A":
				$categorySpecF = "|7";
				break;
			case "B":
				$categorySpecF = "|8";
				break;
			case "C":
				$categorySpecF = "|9";
				break;
			case "D":
				$categorySpecF = "|10";
				break;
			case "E":
				$categorySpecF = "|11";
				break;
			case "F":
				$categorySpecF = "|12";
				break;
			case "G":
				$categorySpecF = "|13";
				break;
			case "H":
				$categorySpecF = "|14";
				break;
			case "I":
				$categorySpecF = "|15";
				break;
			case "J":
				$categorySpecF = "|16";
				break;
			case "K":
				$categorySpecF = "|17";
				break;
			case "L":
				$categorySpecF = "|18";
				break;
			case "M":
				$categorySpecF = "|19";
				break;
			case "N":
				$categorySpecF = "|20";
				break;
			case "Ö":
				$categorySpecF = "|21";
				break;
			case "O":
				$categorySpecF = "|21";
				break;
			case "P":
				$categorySpecF = "|22";
				break;
			case "Q":
				$categorySpecF = "|23";
				break;
			case "R":
				$categorySpecF = "|24";
				break;
			case "S":
				$categorySpecF = "|25";
				break;
			case "T":
				$categorySpecF = "|26";
				break;
			case "Ü":
				$categorySpecF = "|27";
				break;
			case "U":
				$categorySpecF = "|27";
				break;
			case "V":
				$categorySpecF = "|28";
				break;
			case "W":
				$categorySpecF = "|29";
				break;
			case "X":
				$categorySpecF = "|30";
				break;
			case "Y":
				$categorySpecF = "|31";
				break;
			case "Z":
				$categorySpecF = "|32";
				break;
			default:
				$categorySpecF = NULL;
				echo mb_substr($nameF, 0, 1);
				echo $artNrF;
				break;
		}
		$descriptionLongF= "<h2>" . $getFilterdArticleS['Name'] ." kaufen</h2>
		<p><br />Sie erhalten " . $getFilterdArticleS['rowcount'] . " Adressen der gew&auml;hlten Branche als kommagetrennte Textdatei. Diese k&ouml;nnen Sie in den allermeisten F&auml;llen problemlos in Ihre Anwendungsprogramme importieren und verwenden. Aus Platz- und Performancegr&uuml;nden erhalten Sie diese in gepackter Form (ZIP-Datei).<br /><br />Zur Neukundengewinnung ideal auch inkl. E-Mail-Adressen, sofern bekannt und angegeben.<br />Steigern Sie Ihren Umsatz durch unsere gepr&uuml;ften Branchenadressen.<br />Wir verkaufen auch Waschmaschinen<br /></p>";
		$descriptionF = "Branche: " . $getFilterdArticleS['Name'] . " " . $getFilterdArticleS['Bundesland'] . ", Datensätze: " . $getFilterdArticleS['rowcount'];
		
		$createFilterdArticle = $pdo->prepare("INSERT INTO articles (ordernumber, mainnumber, name, supplier, tax, price_EK, imageUrl, active, description, description_long, categories) VALUES (:ordernumber, :mainnumber, :name, :supplier, :tax, :price_EK, :imageUrl, :active, :description, :description_long, :categories)");
		$createFilterdArticleResult = $createFilterdArticle->execute(
				array(
						"ordernumber" => $artNrF,
						"mainnumber" => $artNrF,
						"name" => $getFilterdArticleS['Name'] . " " . $getFilterdArticleS['Bundesland'],
						"supplier" => supplier,
						"tax" => tax,
						"price_EK" => $ekf,
						"imageUrl" => ArtImage,
						"active" => true,
						"description" => $descriptionF,
						"description_long" => $descriptionLongF,
						"categories" => $category.$categorySpecF
				)
			);
		$count++;
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
$file = fopen(path."articlesN.csv", "w");
fputcsv($file, $_SESSION['theaders'], ";", '"');
foreach ($export as $entry){
	fputcsv($file, $entry, ";", '"');
}
fclose($file);