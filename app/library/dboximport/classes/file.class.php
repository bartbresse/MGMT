<?php

class file
{
	public $filename;
	
	public function __construct($filename)
	{
		$this->filename = $filename;
	}
	
	public function delete()
	{
		unlink($this->filename);
	}
}

?>