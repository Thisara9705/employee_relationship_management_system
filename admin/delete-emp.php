<?php include '../db/db.php'; ?>
<?php

if(!$_GET['id'] OR empty($_GET['id']))
{
	header('location: add_emp.php');
}else
{
	$id = (int)$_GET['id'];
	$query = $db->query("TRUNCATE TABLE emp");
	if($query){
		header('location: add_emp.php');
	}
}