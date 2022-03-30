<?php
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
			$db = new PDO('mysql:host=localhost;dbname=adressen;charset=utf8mb4', 'root', '');
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->exec("set names utf8mb4");
			/*
			 * Declaration Section
			 */
			$search1 = $search1."%";
			$search2 = $search2."%";
			$search3 = $search3."%";
			$searchNameN = clear_string($searchName);
			/*
			 * End Declaration Section
			 */
			$headers = '
			SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
			FROM adressen LIMIT 0,1;
			';
			
			$sql = '
			SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
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
			$file = "export_$searchNameN.txt";
			$path = "../export/";
			$logpath = "../export/log/";
			$logfn = "log_Export_".$searchNameN."_".$searchIndex."_".$search1.".log";
			$export = fopen($path.$file, 'w');
			//$log = fopen($logpath.$logfn, 'w');
			$enclosure = '"';
			$delimiter = ';';

			while ($fetch = $stmnt->fetch(PDO::FETCH_ASSOC)){
				array_push($saveset, array_values($fetch));
			}
			fputcsv($export, array_values($_SESSION['theaders']), $delimiter, $enclosure);
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
		$dbcon = new PDO('mysql:host=localhost;dbname=adressen;charset=utf8mb4', 'root', '');
		$dbcon->exec("set names utf8mb4");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$search1 = $search1."%";
		$search2 = $search2."%";
		$search3 = $search3."%";
		$sql = '
		SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
		';
		$headers = '
		SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen LIMIT 0,1;
		';
		
		if(!isset($_SESSION['theaders'])){
			$theaders_stmnt = $dbcon->prepare($headers);
			$theaders_result = $theaders_stmnt->execute();
			$theaders = $theaders_stmnt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['theaders'] = array_keys($theaders);
		}  
		
		if (strlen($search1)<=3 && strlen($search2) <=3 && strlen($search3) <= 3){
			$bund = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
			//$bund_short = array("Baden-Württemberg", "Thüringen");
			foreach ($bund as $index){
				$getbund_statement = $dbcon->prepare($sql);
				$getbund_result = $getbund_statement->execute(array("$index", "$search1", "$search2", "$search3"));
				$bundsave = null;
				$bundsave = array();
				$searchNameN = clear_string($searchName);
				$indexN = clear_string($index);
				$file = "Export_".$searchNameN."_".$indexN.".txt";
				$path = "../exportbund/";
				$enclosure = '"';
				$delimiter = ';';
				//$logpath = "../exportbund/log/";
				//$logfn = "log_Export_".$searchNameN."_".$searchIndex."_".$search1."_".$indexN.".log";
				$export = fopen($path.$file, 'w');
				//$log = fopen($logpath.$logfn, 'w');
				fputcsv($export, array_values($_SESSION['theaders']), $delimiter, $enclosure);
				while ($getbund_fetch = $getbund_statement->fetch(PDO::FETCH_ASSOC)){
					array_push($bundsave, array_values($getbund_fetch));
				}
				foreach ($bundsave as $exportentry){
					fputcsv($export, $exportentry,  $delimiter, $enclosure);
					//fwrite($log, print_r($exportentry, true));
				}
				fclose($export);
				//fclose($log);
			}
			return true;
		}else{
			return false;
		}
	}catch (PDOException $pdoError){
		echo $pdoError->getMessage();
	}
}
function SubExportRcount (){
	define("MinRowReq", 25000, true);
	
	$dbh = new PDO("mysql:host=localhost;dbname=adressen;charset=utf8mb4", "root", "");
	
	$get_baseInfo_statement = $dbh->prepare("SELECT * FROM rowcount");
	$get_baseInfo_result = $get_baseInfo_statement->execute();
	while ($get_baseInfo_dataset = $get_baseInfo_statement->fetch(PDO::FETCH_ASSOC)){
		
		$search1 = $get_baseInfo_dataset['wz2003'];
		$search2 = $get_baseInfo_dataset['wz2003'];
		$search3 = $get_baseInfo_dataset['wz2003'];
		
		$sql = '
			SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
			FROM adressen
			WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
			';
		$headers = '
			SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
			FROM adressen LIMIT 0,1;
			';
		$count = '
			SELECT COUNT(*) `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
			FROM adressen
			WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
			';
		$bund = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
		
		if(!isset($_SESSION['theaders'])){
			$theaders_stmnt = $dbh->prepare($headers);
			$theaders_result = $theaders_stmnt->execute(array("$index", "$search1", "$search2", "$search3"));
			$theaders = $theaders_stmnt->fetch(PDO::FETCH_ASSOC);
			$_SESSION['theaders'] = array_keys($theaders);
		}

		if($get_baseInfo_dataset['rowcount'] >= MinRowReq){
			
			foreach ($bund as $index){
				
				$export_data_statement = $dbh->prepare($sql);
				$export_data_result = $export_data_statement->execute(array("$index", "$search1", "$search2", "$search3"));
				
				$getbund_statement = $dbh->prepare($count);
				$getbund_result = $getbund_statement->execute(array("$index", "$search1", "$search2", "$search3"));
				
				$getbund_rows = $getbund_statement->fetchColumn();
				
				$stmnt = $dbh->prepare("INSERT INTO rowcountbund (Name, Bundesland, wz2003, rowcount) VALUES (:Name, :Bundesland, :wz2003, :rowcount)");
				$res = $stmnt->execute(array('Name' => $get_baseInfo_dataset['Name'], 'Bundesland' => $index, 'wz2003' => $search1, 'rowcount' => $getbund_rows));
				
				
				$searchNameN = clear_string($get_baseInfo_dataset['Name']);
				$indexN = clear_string($index);
				
				$bundsave = null;
				$bundsave = array();
				
				$file = "Export_".$searchNameN."_".$indexN.".txt";
				$path = "../exportbund/";
				$enclosure = '"';
				$delimiter = ';';
				//$logpath = "../exportbund/log/";
				//$logfn = "log_Export_".$searchNameN."_".$searchIndex."_".$search1."_".$indexN.".log";
				$export = fopen($path.$file, 'w');
				//$log = fopen($logpath.$logfn, 'w');
				fputcsv($export, array_values($_SESSION['theaders']), $delimiter, $enclosure);
				
				while($export_data_fetch = $export_data_statement->fetch(PDO::FETCH_ASSOC)){
					array_push($bundsave, array_values($export_data_fetch));
				}
				
				foreach ($bundsave as $exportentry){
					fputcsv($export, $exportentry,  $delimiter, $enclosure);
					//fwrite($log, print_r($exportentry, true));
				}
				fclose($export);
				//fclose($log);
			}
		}
	}
}

function clear_string($str, $how = '-'){
	$search = array("ä", "ö", "ü", "ß", "Ä", "Ö",
			"Ü", "&", "é", "á", "ó",
			" :)", " :D", " :-)", " :P",
			" :O", " ;D", " ;)", " ^^",
			" :|", " :-/", ":)", ":D",
			":-)", ":P", ":O", ";D", ";)",
			"^^", ":|", ":-/", "(", ")", "[", "]",
			"<", ">", "!", "\"", "§", "$", "%", "&",
			"/", "(", ")", "=", "?", "`", "´", "*", "'",
			"_", ":", ";", "²", "³", "{", "}",
			"\\", "~", "#", "+", ".", ",",
			"=", ":", "=)");
	$replace = array("ae", "oe", "ue", "ss", "Ae", "Oe",
			"Ue", "und", "e", "a", "o", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "", "");
	$str = str_replace($search, $replace, $str);
	$str = strtolower(preg_replace("/[^a-zA-Z0-9]+/", trim($how), $str));
	return $str;
}


