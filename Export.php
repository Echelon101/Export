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
 * Name for Export File
 * </p>
 * @return boolean
 */
function QueryExport($search1, $search2, $search3, $searchName, $searchIndex){
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
	
	$saveset = array();
	$stmnt = $db->prepare($sql);
	$result = $stmnt->execute(array("$search1", "$search2", "$search3"));
	
	while ($fetch = $stmnt->fetch(PDO::FETCH_ASSOC)){
		array_push($saveset, array_values($fetch));
	}
	$file = "Export_$searchName.csv";
	$path = "export/";
	$logpath = "export/log/";
	$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1.".log";
	$export = fopen($path.$file, 'w');
	$log = fopen($logpath.$logfn, 'w');
	
	foreach ($saveset as $entry){
		fputcsv($export, $entry);
		//fwrite($log, print_r($entry, true));
		}
	return true;
}
function SubExport($search1, $search2, $search3, $searchName, $searchIndex){
	$dbcon = new PDO('mysql:host=localhost;dbname=adressen', 'root', '');
	$bundsave = array();
	$search1 = $search1."%";
	$search2 = $search2."%";
	$search3 = $search3."%";
	$sql = '
	SELECT *
	FROM adressen
	WHERE Bundesland = ? AMD (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
	';
	if (strlen($search1)<=3 && strlen($search2) <=3 && strlen($search3) <= 3){
		$bund = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
		foreach ($bund as $index){
			$getbund_statement = $dbcon->prepare($sql);
			$getbund_result = $getbund_statement->execute(array("$index", "$search1", "$search2", "$search3"));
			while ($getbund_fetch = $getbund_statement->fetch(PDO::FETCH_ASSOC)){
				array_push($bundsave, array_values($getbund_fetch));
			}
			$file = "Export_".$searchName."_".$index.".csv";
			$path = "exportbund/";
			$logpath = "exportbund/log/";
			$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1."_".$index.".log";
			$export = fopen($path.$file, 'w');
			$log = fopen($logpath.$logfn, 'w');
			echo '<pre>';
			var_dump($index);
			echo '--------------------------------------------------------------------------------------------- <br>';
			var_dump($getbund_fetch);
			echo '--------------------------------------------------------------------------------------------- <br>';
			var_dump($bundsave);
			echo '--------------------------------------------------------------------------------------------- <br>';
			echo '</pre>';
			foreach ($bundsave as $exportentry){
				fputcsv($export, $exportentry);
				//fwrite($log, print_r($exportentry, true));
			}
		}
		return true;
	}else{
		return false;
	}
}
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage() . "<br>";
	return false;
	die();
}


