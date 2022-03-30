<?php
try{
	try {
		session_start();
		
		define("ARTICLE_PREFIX", "AS"); //Prefix der Artikelnummer
		define("ARTICE_NUMBER_LEN", 6); //Länge der Artikenummer ohne Prefix
		define("EXPORT_PATH", "../files/export/"); //Export Pfad
		define("FILE_EXTENSION", ".txt"); //Dateiendung der Exportierten Datei
		define("CSV_DELIMITER", ";"); //Feldtrennung in den Export Dateien
		define("CSV_ENCLOSURE", '"'); //Texteinschluss in den Export Dateien
		define("CURRENT_COUNTRY", "Deutschland"); //Land der Adressen
		define("ARTICLE_NUMBER_OFFSET", 0); //Artikel Nummer Offset, um höhere Artikelnummern zu bekommen
		define("MIN_ADDR_REQ", 10);  //Minimale Adressen pro Export
		define("MIN_ROW_REQ", 1000); //Minimale Adressen, damit nach Bundesländern getrennt wird
		
		$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root" ,"");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$count = 1 + ARTICLE_NUMBER_OFFSET;
		
		$sql = '
		SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?';
		
		$sql2 = '
		SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)';
		
		$counter = "
		SELECT COUNT(*) `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?";
		
		$counter2 = '
		SELECT COUNT(*) `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen
		WHERE Bundesland = ? AND (wz2003_1 LIKE ? OR wz2003_2 LIKE ? OR wz2003_3 LIKE ?)';
	
		$headers = '
		SELECT `Satznr`, `Firmen_KZ`, `Firma1`, `Firma2`, `Firma3`, `Strasse`, `PLZ`, `Ort`, `Telefon`, `Telefax`, `Homepage`, `Email`, `Position`, `Anrede`, `Titel`, `Vorname`, `NN_Praefix`, `Nachname`, `NN_Suffix`, `Brief_Anr`, `BriefTitel`, `WZ2003_1`, `Bezeichn_1`, `WZ2003_2`, `Bezeichn_2`, `WZ2003_3`, `Bezeichn3`, `Ust_ID`, `Amtsgerich`, `Handelsreg`, `HandelArt`, `HandelDatu`, `Ortsteil`, `Ortszusatz`, `Bundesland`, `Vorwahl`, `Leitbereic`, `Einwohner`, `Flaeche`, `KFZ_KZ`, `GeoXY`, `Anz_Mitarb`, `ID_KZ`, `BranSuchBez`, `Land`
		FROM adressen LIMIT 0,1';
		
		$putInfo = '
		INSERT INTO exportlog (ArticleNumber, Name, Region, Country, wz2003, rowcount, filename)
		VALUES (:ArticleNumber, :Name, :Region, :Country, :wz2003, :rowcount, :filename)';
		
		$bundeslander = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");
		
		if(!isset($_SESSION['headers'])){
			$setHeaders_s = $pdo->prepare($headers);
			$setHeaders_r = $setHeaders_s->execute();
			$setHeaders = $setHeaders_s->fetch(PDO::FETCH_ASSOC);
			$_SESSION['headers'] = array_keys($setHeaders);
		}

		$getCategories_stmnt = $pdo->prepare("SELECT * FROM wz2003");
		$getCategories_res = $getCategories_stmnt->execute();
		
		
		while($getCategories_ftch = $getCategories_stmnt->fetch(PDO::FETCH_ASSOC)){
			
			$savesets = null;
			$savesets = array();
			
			$s1 = $getCategories_ftch['WZ2003'];
			$s2 = $getCategories_ftch['WZ2003'];
			$s3 = $getCategories_ftch['WZ2003'];
			
			$get_rowcount_stmnt = $pdo->prepare($counter);
			$get_rowcount_res = $get_rowcount_stmnt->execute(array($s1."%" ,$s2."%" ,$s3."%"));
			$get_rowcount = $get_rowcount_stmnt->fetchColumn();
			
			$export_norm_stmnt = $pdo->prepare($sql);
			$export_norm_res = $export_norm_stmnt->execute(array($s1."%" ,$s2."%" ,$s3."%"));
			if($get_rowcount >= MIN_ADDR_REQ){
				while($export_norm_ftch = $export_norm_stmnt->fetch(PDO::FETCH_ASSOC)){
					array_push($savesets, array_values($export_norm_ftch));
				}
				$fname = ARTICLE_PREFIX . str_pad($count, ARTICE_NUMBER_LEN, "0", STR_PAD_LEFT);
				
				$export = fopen(EXPORT_PATH . $fname . FILE_EXTENSION, "w");
				
				fputcsv($export, array_values($_SESSION['headers']), CSV_DELIMITER, CSV_ENCLOSURE);
				
				foreach ($savesets as $saveset){
					fputcsv($export, $saveset, CSV_DELIMITER, CSV_ENCLOSURE);
				}
				fclose($export);
				
				$put_articleInfo_stmnt = $pdo->prepare($putInfo);
				$put_articleInfo_res = $put_articleInfo_stmnt->execute(array("ArticleNumber" => $fname, "Name" => $getCategories_ftch['Bezeichnung'], "Region" => null, "Country" => CURRENT_COUNTRY, "wz2003" => $s1, "rowcount" => $get_rowcount, "filename" => $fname . FILE_EXTENSION));
							
				$count++;
			}else{
				$log_dropped_stmnt = $pdo->prepare("INSERT INTO droppedexports (name, wz2003, rowcount) VALUES (:name, :wz2003, :rowcount)");
				$log_dropped_res = $log_dropped_stmnt->execute(array("name" => $getCategories_ftch['Bezeichnung'], "wz2003" => $s1, "rowcount" => $get_rowcount));
			}
			if($get_rowcount >= MIN_ROW_REQ){
							
				foreach ($bundeslander as $bundesland){
					$bundsaves = null;
					$bundsaves = array();
					
					$get_rowcount_bund_stmnt = $pdo->prepare($counter2);
					$get_rowcount_bund_res = $get_rowcount_bund_stmnt->execute(array($bundesland, $s1, $s2, $s3));
					$get_rowcount_bund = $get_rowcount_bund_stmnt->fetchColumn();
					
					$export_bund_stmnt = $pdo->prepare($sql2);
					$export_bund_res = $export_bund_stmnt->execute(array($bundesland, $s1, $s2, $s3));
					if($get_rowcount_bund >= MIN_ADDR_REQ){
						while($export_bund_ftch = $export_bund_stmnt->fetch(PDO::FETCH_ASSOC)){
							array_push($bundsaves, array_values($export_bund_ftch));
						}
											
						$fname_bund = ARTICLE_PREFIX . str_pad($count, ARTICE_NUMBER_LEN, "0", STR_PAD_LEFT);
						
						$export_bund = fopen(EXPORT_PATH . $fname_bund . FILE_EXTENSION, "w");
						
						fputcsv($export_bund, array_values($_SESSION['headers']), CSV_DELIMITER, CSV_ENCLOSURE);
						
						foreach ($bundsaves as $bundsave){
							fputcsv($export_bund, $bundsave, CSV_DELIMITER, CSV_ENCLOSURE);
						}
						fclose($export_bund);
						
						$put_bundArticleInfo_stmnt = $pdo->prepare($putInfo);
						$put_bundArticleInfo_res = $put_bundArticleInfo_stmnt->execute(array("ArticleNumber" => $fname_bund, "Name" => $getCategories_ftch['Bezeichnung'], "Region" => $bundesland, "Country" => CURRENT_COUNTRY, "wz2003" => $s1, "rowcount" => $get_rowcount_bund, "filename" => $fname_bund . FILE_EXTENSION));
						
						$count++;
					}else {
						$log_dropped_bund_stmnt = $pdo->prepare("INSERT INTO droppedexports (name, wz2003, rowcount, region) VALUES (:name, :wz2003, :rowcount, :region)");
						$log_dropped_bund_res = $log_dropped_bund_stmnt->execute(array("name" => $getCategories_ftch['Bezeichnung'], "wz2003" => $s1, "rowcount" => $get_rowcount_bund, "region" => $bundesland));
					}
				}
				
			}
		}
		
	}catch (PDOException $e){
		echo $e->getMessage();
	}
	
}catch (Exception $exception){
	echo $exception->getMessage();
}