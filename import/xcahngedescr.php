<?php
try{
	try {
		$pdo = new PDO("mysql:host=localhost;dbname=shopwarebackup;charset=utf8", "root", "");
		$pdo2 = new PDO("mysql:host=localhost;dbname=testshop;charset=utf8", "root", "");
		
		$query = "UPDATE s_articles SET description_long = :description_long WHERE id = :id";
		
		$get_id_stmnt = $pdo->prepare("SELECT id FROM s_articles ORDER BY id DESC LIMIT 0,1");
		$get_id_result = $get_id_stmnt->execute();
		$get_id_fetch = $get_id_stmnt->fetch(PDO::FETCH_ASSOC);
		
		$get_rows_stmnt = $pdo2->prepare("SELECT * FROM rowcount");
		$get_rows_result = $get_rows_stmnt->execute();
		
		$count = 1;
		
		while ($get_rows_fetch = $get_rows_stmnt->fetch(PDO::FETCH_ASSOC)){
			$descrL = "<h2>" . $get_rows_fetch['Name'] ." kaufen</h2><p><br />Sie erhalten " . $get_rows_fetch['rowcount'] . " Adressen der gew&auml;hlten Branche als kommagetrennte Textdatei. Diese k&ouml;nnen Sie in den allermeisten F&auml;llen problemlos in Ihre Anwendungsprogramme importieren und verwenden. Aus Platz- und Performancegr&uuml;nden erhalten Sie diese in gepackter Form (ZIP-Datei).<br /><br />Zur Neukundengewinnung ideal auch inkl. E-Mail-Adressen, sofern bekannt und angegeben.<br />Steigern Sie Ihren Umsatz durch unsere gepr&uuml;ften Branchenadressen.<br /><br /></p>";
			
			$update_stmnt = $pdo->prepare($query);
			$update_result = $update_stmnt->execute(array("description_long" => $descrL, "id" => $count));
			
			$get_rowsF_stmnt = $pdo2->prepare("SELECT * FROM rowcountbund WHERE Name = :Name");
			$get_rowsF_result = $get_rowsF_stmnt->execute(array("Name" => $get_rows_fetch['Name']));
			if(!$update_result){
				echo "Insert Non Filter Count: ".$count." | Fail <br>";
			}elseif ($update_result){
				echo "Insert Non Filter Count: ".$count." | Success <br>";
			}
			$count++;
			while ($get_rowsF_fetch = $get_rowsF_stmnt->fetch(PDO::FETCH_ASSOC)){
				$descrLBund = "<h2>" . $get_rowsF_fetch['Name'] ." kaufen</h2><p><br />Sie erhalten " . $get_rowsF_fetch['rowcount'] . " Adressen der gew&auml;hlten Branche als kommagetrennte Textdatei. Diese k&ouml;nnen Sie in den allermeisten F&auml;llen problemlos in Ihre Anwendungsprogramme importieren und verwenden. Aus Platz- und Performancegr&uuml;nden erhalten Sie diese in gepackter Form (ZIP-Datei).<br /><br />Zur Neukundengewinnung ideal auch inkl. E-Mail-Adressen, sofern bekannt und angegeben.<br />Steigern Sie Ihren Umsatz durch unsere gepr&uuml;ften Branchenadressen.<br /><br /></p>";
				
				$update_stmntF = $pdo->prepare($query);
				$update_resultF = $update_stmntF->execute(array("description_long" => $descrLBund, "id" => $count));
				if(!$update_resultF){
					echo "Insert Filter Count: ".$count." | Fail <br>";
				}elseif ($update_resultF){
					echo "Insert Filter Count: ".$count." | Success <br>";
				}
				$count++;
			}
		}
	}catch (PDOException $e){
		echo $e->getMessage();
	}
}catch (Exception $exc){
	echo  $exc->getMessage();
}