<?php include '../db/db.php'; ?>
<?php

if(!$_GET['id'] OR empty($_GET['id']))
{
	header('location: add_acarder.php');
}else
{
	$id = (int)$_GET['id'];
	$query = $db->query("DELETE FROM `team` WHERE c_date = CURDATE()");
	if($query){
		header('location: add_acarder.php');
	}
}