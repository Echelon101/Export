<?php
try {
//---------------------------------------------------------------------------------------------------------------------------------

/**
 * @param string $search1 <p>
 * Input for Field wz2003_1
 * </p>
 * @param string $search2 <p>
 * Input for Field wz2003_2
 * </p>
 * @param string $search3 <p>
 * Input for Field wz2003_3
 * </p>
 * @param string $searchName <p>
 * Name for export File
 * </p>
 * @return boolean
 */
function QueryExport($search1, $search2, $search3, $searchName, $searchIndex){
	try{
	try{
		echo 'call successful';
	$db = new PDO('mysql:host=localhost;dbname=adressen', 'root', '');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	/*
	 * Declaration Section
	 */
	$search1 = $search1."%";
	$search2 = $search2."%";
	$search3 = $search3."%";
	
	/*
	 * End Declaration Section
	 */
	
	$sql = '
	SELECT *
	FROM adressen
	WHERE wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?
	';
	
	$saveset = array(
			array('"ID"', '"Satznr"', '"Firmen_KZ"', '"Firma1"', '"Firma2"', '"Firma3"', '"Strasse"', '"PLZ"', '"Ort"', '"Telefon"', '"Telefax"', '"Homepage"', '"Email"', '"Position"', '"Anrede"', '"Titel"', '"Vorname"', '"NN_Praefix"', '"Nachname"', '"NN_Suffix"', '"Brief_Anr"', '"BriefTitel"', '"WZ2003_1"', '"Bezeichn_1"', '"WZ2003_2"', '"Bezeichn_2"', '"WZ2003_3"', '"Bezeichn3"', '"Ust_ID"', '"Amtsgerich"', '"Handelsreg"', '"HandelArt"', '"HandelDatu"', '"Ortsteil"', '"Ortszusatz"', '"Bundesland"', '"Vorwahl"', '"Leitbereic"', '"Einwohner"', '"Flaeche"', '"KFZ_KZ"', '"GeoXY"', '"Anz_Mitarb"', '"ID_KZ"', '"BranSuchBez"', '"Land"')
	);
	$stmnt = $db->prepare($sql);
	$result = $stmnt->execute(array("$search1", "$search2", "$search3"));
	
	while ($fetch = $stmnt->fetch(PDO::FETCH_ASSOC)){
		array_push($saveset, array_values($fetch));
	}
	$file = "Export_$searchName.csv";
	$path = "../export/";
	$logpath = "../export/log/";
	$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1.".log";
	$export = fopen($path.$file, 'w');
	$log = fopen($logpath.$logfn, 'w');
	$enclosure = '"';
	$delimiter = ';';
	foreach ($saveset as $entry){
		fputcsv($export, $entry, $delimiter, $enclosure);
		fwrite($log, print_r($entry, true));
		}
	return true;
	}catch (PDOException $pdoE){
		echo $pdoE->getMessage();
	}
	}catch (Exception $e){
		echo $e->getMessage();
	}
}
function SubExport($search1, $search2, $search3, $searchName, $searchIndex){
	try{
	$dbcon = new PDO('mysql:host=localhost;dbname=adressen;charset=utf8', 'root', '');
	$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bundsave = array();
	$search1 = $search1."%";
	$search2 = $search2."%";
	$search3 = $search3."%";
	$sql = '
	SELECT *
	FROM adressen
	WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
	';
	$i=0;
	if (strlen($search1)<=3 && strlen($search2) <=3 && strlen($search3) <= 3){
		$bund = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
		$bund_short = array("Baden-Württemberg", "Thüringen");
		foreach ($bund_short as $index){
			$getbund_statement = $dbcon->prepare($sql);
			$getbund_result = $getbund_statement->execute(array("$index", "$search1", "$search2", "$search3"));
			while ($getbund_fetch = $getbund_statement->fetch(PDO::FETCH_ASSOC)){
				array_push($bundsave, array_values($getbund_fetch));
			}
			$file = "Export_".$searchName."_".$index.".csv";
			$path = "../exportbund/";
			$logpath = "../exportbund/log/";
			$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1."_".$index.".log";
			$export = fopen($path.$file, 'w');
			$log = fopen($logpath.$logfn, 'w');
			foreach ($bundsave as $exportentry){
				fputcsv($export, $exportentry);
				//fwrite($log, print_r($exportentry, true));
			}
		}
		return true;
	}else{
		return false;
	}
	}catch (PDOException $pdoError){
		echo $pdoError->getMessage();
	}
}
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage() . "<br>";
	return false;
	die();
}


