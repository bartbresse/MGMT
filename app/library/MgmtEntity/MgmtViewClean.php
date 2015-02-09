<?php
namespace MgmtEntity;

class MgmtViewClean extends MgmtFile
{
	public function __construct($entity)
	{
		$this->path = '../app/views/'.strtolower($entity->name).'/clean.volt';
		parent::__construct($entity);
	}	

	public function toFile()
	{
		$contents =	file_get_contents(BASEURL.'templates/clean2.rsi');
		$a = array('#entities#','#Entity#','#entity#');
		$b = array($this->entity->name.'s',ucfirst($this->entity->name),strtolower($this->entity->name));
		
		$this->create($a,$b,$contents,$this->path);		
	}
}

?>