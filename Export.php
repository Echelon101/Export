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
		fwrite($log, print_r($entry, true));
		}
	return true;
}
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage() . "<br>";
	return false;
	die();
}




/*
  $create = "CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

$db->query($create);
*/

?>
<form action="?export=true" method="post">
<button type="submit">export</button>
</form>
