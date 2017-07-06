<?php
session_start();
include '../config/config.php';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
$trimmer = trim("-");
echo $trimmer."<br>";
$input = "Ämter und Ärzte und Bauernhöfe";
$trimmed = clear_string($input);

function clear_string($str, $how = '-'){
	$search = array("ä", "ö", "ü", "ß", "Ä", "Ö",
			"Ü", "&", "é", "á", "ó",
			" :)", " :D", " :-)", " :P",
			" :O", " ;D", " ;)", " ^^",
			" :|", " :-/", ":)", ":D",
			":-)", ":P", ":O", ";D", ";)",
			"^^", ":|", ":-/", "(", ")", "[", "]",
			"<", ">", "!", "\"", "§", "$", "%", "&",
			"/", "(", ")", "=", "?", "`", "´", "*", "'",
			"_", ":", ";", "²", "³", "{", "}",
			"\\", "~", "#", "+", ".", ",",
			"=", ":", "=)");
	$replace = array("ae", "oe", "ue", "ss", "Ae", "Oe",
			"Ue", "und", "e", "a", "o", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "",
			"", "", "", "", "", "", "", "", "", "");
	$str = str_replace($search, $replace, $str);
	echo $str.'<br>';
	$str = strtolower(preg_replace("/[^a-zA-Z0-9]+/", trim($how), $str));
	echo $str.'<br>';
	return $str;
}