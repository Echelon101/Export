<?php
include '../config/config.php';
function SubExport($search1, $search2, $search3, $searchName, $searchIndex){
	$dbcon = new PDO('mysql:host=localhost;dbname=adressen', 'root', '');
	$bundsave = array();
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
			$logpath = "exportbund//log/";
			$logfn = "log_Export_".$searchName."_".$searchIndex."_".$search1."_".$index.".log";
			$export = fopen($path.$file, 'w');
			$log = fopen($logpath.$logfn, 'w');
			foreach ($bundsave as $exportentry){
				fputcsv($export, $exportentry);
				//fwrite($log, print_r($exportentry, true));
			}
		}
	}else{
		return false;
	}
}