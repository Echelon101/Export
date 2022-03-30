<?php
include 'counter.php';
include '../config/config.php';
$dbcon = new PDO("$driver:host=$hostname;port=$port;dbname=$dbname;charset=utf8mb4", "$dbuser", "$dbpassword");

$sql = "
SELECT * 
FROM Branchen 
WHERE BranchenID IS NOT NULL
";

$get_Info_statement = $dbcon->prepare($sql);
$get_Info_result = $get_Info_statement->execute();
while ($get_Info_fetch = $get_Info_statement->fetch(PDO::FETCH_ASSOC)){
	CountSubExport($get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenName'], $get_Info_fetch['ID']);
	//QueryExport($get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenName'], $get_Info_fetch['ID']);
}

