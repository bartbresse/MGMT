<?php
class backupdb extends backup
{	
	public function dump($credentials)
	{
		$file = $this->filename.'.sql.gz';
	
		$link = mysqli_connect($credentials->mysql->host,$credentials->mysql->username,$credentials->mysql->password,"beautyspot_hwrks") or die("Error " . mysqli_error($link));
	
		$command = "mysqldump --opt -h'".$credentials->mysql->host."' -u'".$credentials->mysql->username."' -p'".$credentials->mysql->password."' 'beautyspot_hwrks' | gzip -9 > ".$file;
		
		echo "mysqldump --opt -h'".$credentials->mysql->host."' -u'".$credentials->mysql->username."' -p'".$credentials->mysql->password."' 'beautyspot_hwrks' | gzip -9 > ".$file;
		exec($command);
		
		return new file($file);
	}
	
	public function delete()
	{
		unlink($this->filename.'.sql.gz');
	}
}
?>