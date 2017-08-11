<?php
$pdo = new PDO("mysql:host=localhost;dbname=testing;charset=utf8", "root", "");


$datetimestmnt = $pdo->prepare("INSERT INTO datetime (datetime) VALUES (?)");
$datetimeresult = $datetimestmnt->execute(array("CURRENT_TIMESTAMP()"));

if($datetimeresult){
	$getDateTimeStmnt = $pdo->prepare("SELECT * FROM datetime");
	$getDateTimeResult = $getDateTimeStmnt->execute();
	
	while ($getDateTime = $getDateTimeStmnt->fetch(PDO::FETCH_ASSOC)){
		echo "<pre>";
		var_dump($getDateTime);
		echo "</pre>";
	}
}