<?php
$pdo = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");

$getstr_stmnt = $pdo->prepare("SELECT * FROM wz2003");
$getstr_res = $getstr_stmnt->execute();
$i = 0;
$s = 0;
while($getstr_ftch = $getstr_stmnt->fetch(PDO::FETCH_ASSOC)){
	$len = strlen($getstr_ftch['Bezeichnung']);
	
	if($len > 100){
		$i++;
	}
	$strArr = explode(" ", $getstr_ftch['Bezeichnung']);
	$ArrLen = strlen($strArr[0]);
	if($ArrLen > 30){
		$s++;
	}
}

echo $i ." ". $s;