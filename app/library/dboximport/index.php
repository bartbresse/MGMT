<html>
<head>
	<meta name="robots" content="noindex, nofollow">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css" rel="stylesheet">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<script>
	function save(location)
	{
		if(true)
		{
				
		}
		else
		{
			window.location.href=location;
		}
	}
</script>
<?php
	include('classes/file.class.php');
	include('classes/jsonfile.class.php');

	$jsonfile = new jsonfile('config.json');
	
	if(count($jsonfile->get()) < 5)
	{
		include('install.php');
	}
?>
<br />
<div class="btn-group">
  <button onclick="save('autobackup.php');" type="button" class="btn btn-danger">BACKUP TO DROPBOX</button>
</div>
</div>
</body>
</html>









