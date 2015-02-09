<?
include 'classes/file.class.php';
include 'classes/jsonfile.class.php';
include 'classes/backup.class.php';
include 'classes/backupdb.class.php';
include 'classes/dbox.class.php';

$file = new jsonfile('config.json');
$credentials = $file->get();

$backupdb = new backupdb('backup','../felix');
$databasebackup = $backupdb->dump($credentials);

$dropbox = new dbox;
$dropbox->upload($databasebackup);

$backupdb->delete();


if(!php_sapi_name() === 'cli')
{
?>
<script>
window.location.href='index.php'; 
</script>
<?
}
?>