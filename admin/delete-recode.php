<?php include '../db/db.php'; ?>
<?php

if(!$_GET['id'] OR empty($_GET['id']))
{
	header('location: details21.php');
}else
{
	$id = (int)$_GET['id'];
	$query = $db->query("DELETE FROM attendance WHERE aid = $id");
	if($query){
		header('location: details21.php');
	}
}
