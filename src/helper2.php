<?php

function database (){
	$mysql = new PDO ("mysql:host=127.0.0.1:8889;dbname=noobs","root","root");
	return $mysql;
}


function sanitize($string){
	$string = stripslashes($string);
	$string = htmlspecialchars($string);
	return $string;
}