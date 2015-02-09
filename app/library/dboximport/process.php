<?php
	include('classes/file.class.php');
	include('classes/jsonfile.class.php');

	$status = array('status' => 'noop');
	
	$jsonfile = new jsonfile('config.json');
	$json = $jsonfile->get();	
	
	$json->mysql->host = $_POST['host']; 
	$json->mysql->username = $_POST['username']; 
	$json->mysql->password = $_POST['password']; 
	$json->mysql->database = $_POST['database'];
	
	$json->dir = $_POST['dir'];
	$json->name = $_POST['name'];
	
	if($jsonfile->set($json))
	{
		$status['status'] = 'ok';
	}
	echo json_encode($status);
?>