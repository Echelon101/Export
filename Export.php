<?php
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
$showExport =true;
$email = 'root@root.com';
if(isset($_GET['export'])){
	$statement = $pdo->prepare('SELECT * FROM adressen');
	$result = $statement->execute();
	$user = $statement->fetchAll();
	if($result){
		$filename = "export_". date("Y-m-d")."_".date("h-i-sa");
		$logname = "log_". date("Y-m-d")."_".date("h-i-sa");
		$file = fopen("export/$filename.csv",'w');
		$log = fopen("log/$logname.log",'w');
		foreach ($user as $entry){
			fputcsv($file, $entry);
			fwrite($log, print_r($entry, true));
			$showExport = false;
		}
	}
}
?>
<?php if($showExport){?>
<form action="?export=1" method="post">
<button type="submit">Export</button>
</form>
<?php }else{?>
<form action="?reset" method="post">
<button type="submit">Reset</button>
</form>
<?php }?>