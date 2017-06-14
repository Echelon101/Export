<?php
try {
$db = new PDO('mysql:host=localhost;dbname=test', 'db0001', '25451');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = '
SELECT *
FROM adressen
WHERE wz2003_1 LIKE "93.04.2" OR wz2003_2 LIKE "93.04.2" OR wz2003_3 LIKE "93.04.2"
';
$stmnt = $db->query($sql);
while ($fetch = $stmnt->fetch(PDO::FETCH_ASSOC)){
	echo '<pre>';
	print_r($fetch);
	echo '</pre>';
}
echo '<pre>';
print_r($fetch);
echo '</pre>';

} catch (PDOException $e) {
	echo "Error: " . $e->getMessage() . "<br>";
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