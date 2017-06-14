<?php
include 'config/config.php';
include 'tools/helper.php';
$ID = "93.04.2";
$view = false;
if(isset($_GET['export'])){
	$pdo = new PDO("$driver:host=$hostname;port=$port;dbname=$dbname", "$dbuser", "$dbpassword");
	$pdo->setAttribute(PDO::ATTR_TIMEOUT, 360);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//$statement = $pdo->prepare('SELECT * FROM adressen WHERE wz2003_1 LIKE :id OR wz2003_2 LIKE :id OR wz2003_3 LIKE :id');
	//$result = $statement->execute(array("id" => $ID.'%'));
	//$save = $statement->fetchAll();
	//$saveset = array();
	$query = $pdo->query('SELECT * FROM adressen WHERE wz2003_1 LIKE :id OR wz2003_2 LIKE :id OR wz2003_3 LIKE :id');
	$get = $query->fetchAll();
	//while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
	//	array_push($saveset, array_values($row));
	//}
	/*$filename = "export_". date("Y-m-d")."_".date("h-i-sa");
	$logname = "log_". date("Y-m-d")."_".date("h-i-sa");
	$exportcsv = fopen("export/$filename.csv",'w');
	$log = fopen("log/$logname.log",'w');
	foreach ($saveset as $entry){
		fputcsv($exportcsv, $entry);
		fwrite($log, print_r($entry, true));
	}*/
	$view = true;
}

?>

<form action="?export=1" method="post">
<button type="submit">Export</button>
</form>

<?php 
if($view){
	/*echo '$saveset';
	echo '<pre>';
	print_r($saveset);
	echo '</pre>';
	
	echo '$row';
	echo '<pre>';
	print_r($row);
	echo '</pre>';*/
	
	echo '$row';
	echo '<pre>';
	print_r($get);
	echo '</pre>';
}
?>