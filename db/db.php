<?php
date_default_timezone_set('Asia/Colombo');
ob_start();
session_start();
try {
	//$db = new PDO('mysql:host=localhost;dbname=u778501372_mas_hrdb','u778501372_mashr','V2f=DvdUR3a');
		$db = new PDO('mysql:host=localhost;dbname=u778501372_mas_er','u778501372_mas_er','#1Pp>]c3w71k');
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die('<h4 style="color:red">Incorrect Connection Details</h4>');
}