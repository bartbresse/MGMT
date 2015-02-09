<?php

include 'Dropbox/autoload.php';
 
use \Dropbox as dbx;

class dbox
{
	public $file;

	public function upload($file)
	{
		$dbxClient = new dbx\Client("oJUg9SfNKy0AAAAAAAAAAc-YVEc_2CJSvRmDqJHroAT-JOczZM3tncqJpTzMYDef", "PHP-Example/1.0");
		
		$f = fopen($file->filename, "rb");
		
		$result = $dbxClient->uploadFile('/'.$file->filename, dbx\WriteMode::add(), $f);
	}
	
	public function download($file)
	{
	
	}
}



?>