<?php	
namespace MgmtFile;

class MgmtFileTree
{		
	public function __construct()
	{
		
	}
	
	public function generateStructure()
	{	
		$this->createFolder('../../uploads/'.date('Y/m').'/');
	}
	
	public function cleanStructure()
	{
		$this->deleteFolder('../../uploads/'.date('Y/m').'/');
	}
	
	function is_dir_empty($dir) 
	{
	  if (!is_readable($dir)) return NULL; 
	  return (count(scandir($dir)) == 2);
	}
	
	public function deleteFolder($path)
	{	
		$dirPath = $path;
		if (! is_dir($dirPath)) {
		//	throw new InvalidArgumentException("$dirPath must be a directory");
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		
		$files = glob($dirPath . '*', GLOB_MARK);
		if(is_array($files) && count($files) > 0)
		{
			foreach ($files as $file){
				if (is_dir($file)) {
					
					if($this->is_dir_empty($file))
					{
						rmdir($file);
					}
					else
					{
						self::deleteFolder($file);
					}
					
				} else {
					unlink($file);
				}
			}
		}
		else
		{
			print_r($files);
		}	
	}
	
	public function createFolder($path)
	{
		//$foldername = strtolower(str_replace('_','',ucfirst($this->entity->name)));			
		if(!is_dir($path))
		{
			mkdir($path, 0777, true);
		}
		return $path;
	}
}
?>