<?php
namespace MgmtEntity;

class MgmtModule
{
    //checks if this entity is already stored in db
    public $id;
    public $name;
    private $entity;

    public function columnMap()
    {
        
    }
    
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function set($entity)
    {
        
    }

    public function generate()
    {
        $mgmtcontroller = new MgmtController($this->entity);
        $mgmtcontroller->toFile('backendcontroller3.rsi');

        $viewfolder = new MgmtViewFolder($this->entity);
        $viewfolder->toFile();

        $indexview = new MgmtViewStats($this->entity);
        $indexview->toFile();
        
        $layoutview = new MgmtViewLayout($this->entity);
        $layoutview->toFile();
    }

    public function delete()
    {
        $mgmtcontroller = new MgmtController($this->entity);
        $mgmtcontroller->delete();

        $viewfolder = new MgmtViewFolder($this->entity);
        $viewfolder->delete();

        $indexview = new MgmtViewStats($this->entity);
        $indexview->delete();
        
        $layoutview = new MgmtViewLayout($this->entity);
        $layoutview->delete();
    }
}

?>