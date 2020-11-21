<?php

require "helper2.php";

session_start();
if (isset($_GET["deadbeef"])){
	$db = database();
	$cName = $_GET["deadbeef"][0];
	$cHName = $_GET["deadbeef"][1];
	$cName = sanitize($cName);
	$cHName = sanitize($cHName);

$result = $db->prepare("INSERT INTO logd (cName, cHName) VALUES (:cName, :cHName)");
$result->execute(array("cName" => $cName, "cHName" => $cHName));


}