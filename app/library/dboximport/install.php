<?php

include 'Dropbox/autoload.php';
 
use \Dropbox as dbx;
?>
<script>

function save(location)
{
	var data = {};
	$(".form-control").each(function() {
		id = $(this).attr('id');
		val = $(this).val();	
		data[id] = val;
	});
	
	$.ajax({
		url: 'process.php',
		type: 'POST',
		dataType: 'json',
		data: data,
		success: function(data)
		{ 
			if(data.status == 'ok')
			{ 
				window.location.href=location; 
			} 
		}
		});		
}

</script>
<?php
	$credentials = array('mysql' => array('username' => 'dev8hw_admin','password' => 'eNiaX5pN','host' => 'localhost','mysql' => 'dev8hw_felix'));
	
	$json = $jsonfile->get();
	
	if(!isset($json->authkey))
	{
		$appInfo = dbx\AppInfo::loadFromJsonFile("config.json");
		$webAuth = new dbx\WebAuthNoRedirect($appInfo, "hetworksbackup2");
			
		$authorizeUrl = $webAuth->start();
		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['access-token'])
		{
			$_POST['access-token'];
			$authCode = \trim($_POST['access-token']);
		
			list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
			$dbxClient = new dbx\Client($accessToken, "PHP-Example/1.0");
			$accountInfo = $dbxClient->getAccountInfo();
			if(count($accountInfo) < 4)
			{
				echo 'WRONG ACCESS TOKEN';
			}
			else
			{
				$f = fopen("auth.txt","w");
				echo $accessToken;
				fwrite($f, $accessToken);
				fclose($f);
				?><script>window.location.href='index.php';</script><?
			}
		}
		?>
		
		<h1>1 Dropbox connection</h1>
		<hr />
		<form action="" method="post">
		1. Go to: <?=$authorizeUrl;?><br />
		2. Click <b>Allow</b> (you might have to log in first)<br />
		3. Copy the authorization code.<br />
		4. Paste here: <input style="width:300px;" type="text" name="access-token" value="" placeholder="access-token" /><br />
		5. Submit: <input type="submit" value="register backup" />
		</form>
		<?
	}
	
	//CONNECT TO A MYSQL DATABASE
		
		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['username'])
		{
			$link = mysql_connect($_POST['host'], $_POST['username'], $_POST['password']);
			if (!$mysqli->real_connect($_POST['host'], $_POST['username'], $_POST['password'],  $_POST['database'])) 
			{ 
			  
			}
			else
			{ echo 'Are you sure this is the right combination?'; }
		}
		?>
		<h3>2 database connection</h3>
		<hr />
	
		<table>
			<tr><td><input class="form-control" type="text" value="<? if(isset($json->mysql->host)){ echo $json->mysql->host; }else{ echo $_SERVER['SERVER_NAME']; } ?>" placeholder="host" name="host" id="host"/></td></tr>
			<tr><td><input class="form-control" type="text" value="<? if(isset($json->mysql->username)){ echo $json->mysql->username; } ?>" placeholder="username" name="username" id="username"/></td></tr>
			<tr><td><input class="form-control" type="password" value="<? if(isset($json->mysql->password)){ echo $json->mysql->password; } ?>" placeholder="password" name="password"  id="password"/></td></tr>
			<tr><td><input class="form-control" type="text" value="<? if(isset($json->mysql->database)){ echo $json->mysql->database; } ?>" placeholder="database" name="database" id="database" /></td></tr>
		</table>
	
		
	
		<h3>3 Directory</h3>
		<hr />
		<table>
			<tr><td><input class="form-control" type="text" id="dir" name="dir" value="<? if(isset($json->dir)){ echo $json->dir; } ?>" placeholder="directory" /></td></tr>	
		</table>
	
	
		<h3>4 Name backup</h3>
		<hr />
		<table>
			<tr><td><input class="form-control" type="text" id="name" name="name" value="<? if(isset($json->name)){ echo $json->name; } ?>" placeholder="Backup name" /></td></tr>	
		</table>
