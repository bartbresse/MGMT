<?php
namespace MgmtEntity;

class MgmtFile
{
	protected $entity;
	protected $path;
	
	public function __construct($entity)
	{	
		$this->entity = $entity;
	}	
	
	public function delete()
	{
		if(is_file($this->path) && isset($this->entity->name))
		{
			unlink($this->path);
		}
	}
	
	
	protected function create($a,$b,$contents,$file)
	{
            $model = str_replace($a,$b,$contents);
            $file = str_replace('_','',$file);

            if(is_file($file)){ chmod($file,0777); } 
            if(strlen($model) > 10)
            { 
                file_put_contents($file,$model);
                chmod($file,0777); 
            } 
            else
            { return false; }
            return true;
	}
}

?>