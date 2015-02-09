<?php

class jsonfile extends file
{
	private $json;
	public function getarray()
	{
		return json_decode(file_get_contents($this->filename),true);
	}
	
	public function get()
	{
		return json_decode(file_get_contents($this->filename));
	}
	
	public function set($json)
	{
		if(file_put_contents($this->filename, json_encode($json)))
		{
			return true;
		}
		else
		{
			echo 'failed opening/writing to file (file rights issue)';
		}	
	}
}

?>