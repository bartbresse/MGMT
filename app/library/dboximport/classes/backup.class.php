<?php
class backup
{
	protected $filename;
	protected $folder;
	private $files = array();
	private $folders = array();
	
	public function __construct($filename,$folder)
	{
		$this->filename = $filename;
		$this->folder = $folder;
	}
	
	public function addfile()
	{
		
	}
	
	public function targz()
	{
			ini_set("memory_limit","300M");
	
			$a = new PharData($this->filename.date("d-m-y").'.tar');
			$a->buildFromDirectory($this->folder.'/app');
			$a->buildFromDirectory($this->folder.'/public');
			$a->buildFromDirectory($this->folder.'/uploads');
			
			if($a->compress(Phar::GZ))
			{
				return new file($this->filename.date("d-m-y").'.tar.gz');
			}
	}
	
	public function delete()
	{
		unlink($this->filename.date("d-m-y").'.tar');
		unlink($this->filename.date("d-m-y").'.tar.gz');
	}
}
?>