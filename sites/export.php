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
	$headers = '
	SELECT * FROM adressen LIMIT 0,1;
	';
	
	$sql = '
	SELECT *
	FROM adressen
	WHERE wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?
	';
	if(!isset($_SESSION['theaders'])){
		$theaders_stmnt = $db->prepare($headers);
		$theaders_result = $theaders_stmnt->execute();
		$theaders = $theaders_stmnt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['theaders'] = array_keys($theaders);
	}
	$saveset = array();
	$stmnt = $db->prepare($sql);
	$result = $stmnt->execute(array("$search1", "$search2", "$search3"));
	
	$file = "Export_$searchName.txt";
	$path = "../export/";
	$logpath = "../export/log/";
	$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1.".log";
	$export = fopen($path.$file, 'w');
	//$log = fopen($logpath.$logfn, 'w');
	$enclosure = '"';
	$delimiter = ';';
	
	while ($fetch = $stmnt->fetch(PDO::FETCH_ASSOC)){
		array_push($saveset, array_values($fetch));
	}
	fputcsv($export, $_SESSION['theaders'], $delimiter, $enclosure);
	//fwrite($log, print_r($_SESSION['theaders'], true));
	foreach ($saveset as $entry){
		fputcsv($export, $entry, $delimiter, $enclosure);
		//fwrite($log, print_r($entry, true));
		}
		//$data = fread($export, filesize($path.$file));
		fclose($export);
		//fclose($log);
		//$fname = $path.$file.'.gz';
		//$zp = gzopen($fname, "w9");
		//gzwrite($zp, $data);
		//gzclose($zp);
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
			$file = "Export_".$searchName."_".$index.".txt";
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


