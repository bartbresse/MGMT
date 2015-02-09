<?php
namespace MgmtEntity;

class MgmtViewLayout extends MgmtFile
{
    protected $path;
    protected $name;
    
    public function __construct($entity)
    {
        $this->name = $entity->name;
        $this->path = '../app/views/layouts/'.$this->name.'.volt';
        parent::__construct($entity);
    }

    public function deleteFile()
    {
        $file = '../app/models/'.$this->name.'.php';
        $this->deleteFile($file);
    }

    public function toFile()
    {
        $contents = file_get_contents(BASEURL.'templates/layout.rsi');
        $a = array('#name#');
        $b = array(ucfirst($this->entity->name));

        $this->create($a,$b,$contents,$this->path);		
    }
}

?>