<?
error_reporting(E_ALL);
ini_set('display_errors', '1');

include 'classes/file.class.php';
include 'classes/jsonfile.class.php';
include 'classes/backup.class.php';
include 'classes/backupdb.class.php';
include 'classes/dbox.class.php';

$file = new jsonfile('config.json');
$credentials = $file->get();

$backup = new backup($credentials->name,$credentials->dir);
$filebackup = $backup->targz();

$backupdb = new backupdb($credentials->name,$credentials->dir);
$databasebackup = $backupdb->dump($credentials);

$dropbox = new dbox($credentials->key);
$dropbox->upload($databasebackup);
$dropbox->upload($filebackup);

$backupdb->delete();
$backup->delete();

if(!php_sapi_name() === 'cli')
{	?>
	<script>
	window.location.href='index.php'; 
	</script>
<? } ?>
