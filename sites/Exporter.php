<?php
include 'config/config.php';
include 'tools/helper.php';

$pdo = new PDO("$driver:host=$hostname;port=$port;dbname=$dbname", "$dbuser", "$dbpassword");

$statement = $pdo->prepare("SELECT * FROM adressen LIMIT 0, 1000");
$result = $statement->execute();
$saveset = array();

while ($row = $statement->fetch(PDO::FETCH_ASSOC)){
	array_push($saveset, array_values($row));
}
$filename = "export_". date("Y-m-d")."_".date("h-i-sa");
$logname = "log_". date("Y-m-d")."_".date("h-i-sa");
$exportcsv = fopen("export/$filename.csv",'w');
$log = fopen("log/$logname.log",'w');
foreach ($saveset as $entry){
	fputcsv($exportcsv, $entry);
	fwrite($log, print_r($entry, true));
}
/*
echo '<pre>';
$index = max(array_keys($content));
echo $index;
echo '</pre>';


echo '------------------------------------------------------------------------------------------';

echo '<pre>';
print_r($content);
echo '</pre>';*/