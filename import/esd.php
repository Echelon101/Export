<?php
define("ArticleIDOffset", 1);
define("FileExt", ".zip");

$pdo = new PDO("mysql:host=localhost;dbname=shopware;charset=utf8", "root" , "");


$sections = array("Baden-Württemberg","Bayern","Berlin","Brandenburg","Bremen","Hamburg","Hessen","Mecklenburg-Vorpommern", "Niedersachsen","Nordrhein-Westfalen","Rheinland-Pfalz","Saarland","Sachsen","Sachsen-Anhalt","Schleswig-Holstein","Thüringen");

$getArticleInfoStmnt = $pdo->prepare("SELECT * FROM s_articles");
$getArticleInfoReslt = $getArticleInfoStmnt->execute();
$i = 0;
while ($getArticleInfo = $getArticleInfoStmnt->fetch(PDO::FETCH_ASSOC)){
	$strArr = explode(" ", $getArticleInfo['name']);
	$strNamePref = "export_";
	$strName = null;
	foreach ($sections as $section){
		$key = array_search($section, $strArr);
		if(!$key){
			$strName2 = null;
		}else{
			$strName2 = $strArr[$key];
			break;
		}
	}
	if(!$key){
		$articleName = clear_string($getArticleInfo['name']);
		$Filename = $strNamePref . $articleName . FileExt;
	}else{
		for($x = 0; $x < $key; $x++){
			$strName .= $strArr[$x]. " ";
		}
		$strNameN = clear_string($strName);
		$strName2N = clear_string($strName2);
		$Filename = $strNamePref . substr($strNameN, 0 ,-1) . "_" . $strName2N . FileExt;
	}
	
	$createESDstmnt = $pdo->prepare("INSERT INTO s_articles_esd (articleID, articledetailsID, file, datum) VALUES (:articleID, :articledetailsID, :file, :datum)");
	$createESDreslt = $createESDstmnt->execute(
				array(
						"articleID" => $getArticleInfo['id'],
						"articledetailsID" => $getArticleInfo['id'],
						"file" => $Filename,
						"datum" => "now()",
				)
			);
}

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
	$str = strtolower(preg_replace("/[^a-zA-Z0-9]+/", trim($how), $str));
	return $str;
}