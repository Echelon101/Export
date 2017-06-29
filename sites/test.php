<?php
include '../config/config.php';

$pdo = new PDO("$driver:host=$hostname;port=$port;dbname=test", "$dbuser", "$dbpassword");
$statement = $pdo->prepare("SELECT * FROM adressen LIMIT 0,1");
$result = $statement->execute();
$fetch = $statement->fetchObject();

echo '<pre>';
print_r($fetch);
echo '</pre>';