<?php
namespace MgmtEntity;

class MgmtViewStats extends MgmtFile
{
    public function __construct($entity)
    {
            $this->path = '../app/views/'.strtolower(ucfirst($entity->name)).'/index.volt';
            parent::__construct($entity);
    }	

    public function toFile()
    {
            //overview
            $contents =	file_get_contents(BASEURL.'templates/stats.rsi');
            $a = array('#Entity#','#entity#','#alias#');
            $b = array(ucfirst($this->entity->name),strtolower($this->entity->name),$this->entity->alias);

            $this->create($a,$b,$contents,$this->path);		
    }
}


?>