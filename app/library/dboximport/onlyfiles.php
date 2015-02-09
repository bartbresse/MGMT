<?
include 'classes/file.class.php';
include 'classes/jsonfile.class.php';
include 'classes/backup.class.php';
include 'classes/backupdb.class.php';
include 'classes/dbox.class.php';

$file = new jsonfile('config.json');
$credentials = $file->get();

$backup = new backup('backup','../felix');
$filebackup = $backup->targz();

$dropbox = new dbox;
$dropbox->upload($filebackup);

$backup->delete();

if(!php_sapi_name() === 'cli')
{	?>

	<script>
	window.location.href='index.php'; 
	</script>
	
<? } ?>