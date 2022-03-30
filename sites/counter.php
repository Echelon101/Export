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
function CountExport($search1, $search2, $search3, $searchName, $searchIndex){
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
			$sql = "
			SELECT COUNT(*) `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
			FROM adressen
			WHERE wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?
			";
			
			$stmnt = $db->prepare($sql);
			$res = $stmnt->execute(array("$search1", "$search2", "$search3"));
			$rowCnt = $stmnt->fetchColumn();
			if(!$res){
				die('Fehler bei der Select Query');
			}
			
			$insertCount = $db->prepare("INSERT INTO rowcount (Name, wz2003, rowcount) VALUES (:Name, :wz2003, :rowcount)");
			$insertCountres = $insertCount->execute(array('Name' => $searchName, 'wz2003' => $search1, 'rowcount' => $rowCnt));
			if(!$insertCountres){
				die('Fehler bei der Instert Query');
			}
			return true;
		}catch (PDOException $pdoE){
			echo $pdoE->getMessage(); 
			return false;
		}
	}catch (Exception $e){
		echo $e->getMessage();
		return false;
	}
}

function CountSubExport($search1, $search2, $search3, $searchName, $searchIndex){
	try{
		$dbcon = new PDO('mysql:host=localhost;dbname=adressen;charset=utf8mb4', 'root', '');
		$dbcon->exec("set names utf8mb4");
		$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$search1 = $search1."%";
		$search2 = $search2."%";
		$search3 = $search3."%";
		$sql = '
		SELECT COUNT(*) `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)
		';
				
		if (strlen($search1)<=3 && strlen($search2) <=3 && strlen($search3) <= 3){
			$bund = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
			//$bund_short = array("Baden-Württemberg", "Thüringen");
			foreach ($bund as $index){
				$getbund_statement = $dbcon->prepare($sql);
				$getbund_result = $getbund_statement->execute(array("$index", "$search1", "$search2", "$search3"));
				
				$searchNameN = clear_string($searchName);
				$indexN = clear_string($index);
				
				$getbund_rows = $getbund_statement->fetchColumn();
				
				$stmnt = $dbcon->prepare("INSERT INTO rowcountbund (Name, Bundesland, wz2003, rowcount) VALUES (:Name, :Bundesland, :wz2003, :rowcount)");
				$res = $stmnt->execute(array('Name' => $searchName, 'Bundesland' => $index, 'wz2003' => $search1, 'rowcount' => $getbund_rows));
						
			}
			return true;
		}else{
			return false;
		}
	}catch (PDOException $pdoError){
		echo $pdoError->getMessage();
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


