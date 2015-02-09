<?php
require_once "Dropbox/autoload.php";
		
use \Dropbox as dbx;

$dbxClient = new dbx\Client("oJUg9SfNKy0AAAAAAAAAAc-YVEc_2CJSvRmDqJHroAT-JOczZM3tncqJpTzMYDef", "PHP-Example/1.0");

$f = fopen("test.php", "rb");
$result = $dbxClient->uploadFile('/test.php', dbx\WriteMode::add(), $f);	

?>