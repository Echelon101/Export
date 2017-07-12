<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		
	</head>
	<body>
		<?php
		try{
			include 'counter.php';
			include '../config/config.php';
			$dbcon = new PDO("$driver:host=$hostname;port=$port;dbname=$dbname;charset=utf8mb4", "$dbuser", "$dbpassword");
			
			$sql = "
			SELECT * 
			FROM branchen 
			WHERE BranchenID IS NOT NULL
			";
			
			$get_Info_statement = $dbcon->prepare($sql);
			$get_Info_result = $get_Info_statement->execute();
			while ($get_Info_fetch = $get_Info_statement->fetch(PDO::FETCH_ASSOC)){
				$count = CountExport($get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenID'], $get_Info_fetch['BranchenName'], $get_Info_fetch['ID']);
				if(!$count){
					break;
				}
		}
		}catch (Exception $e){
			echo $e->getMessage();
		}
		?>
	</body>
</html>
