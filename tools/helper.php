<?php
function toSaveSet($inputarray){
	
	for ($i = 0; $i < max(array_keys($inputarray)); $i++){
		$saveset = array_fill($inputarray[$i], 1, $inputarray[$i]);
		return $saveset;
	}
}