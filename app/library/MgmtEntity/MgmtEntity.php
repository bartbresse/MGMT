<?php

namespace MgmtEntity;

class MgmtEntity
{
	public $id;
	public $lines = array();
	public $name;
	public $alias;
	public $columns = array();
	public $order = array();
	public $clearance;
	public $newtext;
	public $comment; 
	public $module;
	public $formcolumns = array();
	public $class = 'form-control';
	public $relations = array();
	public $widgets = array();
	//checks if this entity is already stored in db
	public $update;
	
	public function __construct($name)
	{
            $this->name = $name;
            $this->class = 'form-control'.rand(1000,9999);
	}
	
	public function set($entity)
	{
            $this->id = $entity->id;
            $this->name = $entity->name;
            $this->alias = $entity->alias;
            $this->clearance = $entity->clearance;
	}
	
	public function addRelation($relation)
	{
            array_push($this->relations,$relation);
	}
	
	public function addLine($line)
	{
            $line->name = strtolower(preg_replace("/[^a-zA-Z]/", "", $line->name));

            if($line->show == 1)
            {
                    array_push($this->columns,$line->name);
                    array_push($this->order,$line->name);
            }
            array_push($this->lines,$line);
	}
	
	public function findLine($name)
	{
            $lines = $this->lines;
            foreach($lines as $line)
            {
                    if($line->name == $name)
                    {
                            return $line;
                    }
            }
            return false;
	}
	
	public function listLines()
	{
		$lines = $this->lines;
		$list = array();
		foreach($lines as $line)
		{
			array_push($list,$line->name);
		}
		return $list;
	}
	
	public function getVisibleColumns()
	{
		$visiblecolumns = array();
		$columns = $this->formcolumns;
		foreach($columns as $column)
		{
			if($column->show == 1)
			{
				array_push($visiblecolumns,$column->titel);
			}
		}
		return $visiblecolumns;
	}
	
	public function generate()
	{
                
            
            
		//create model
		$model = new MgmtModel($this);
		$model->toFile();
		//create controller
		$controller = new MgmtController($this);
		$controller->toFile();
		//createviews
	        $viewfolder = new MgmtViewFolder($this);
		$viewfolder->toFile();
                 
		$addview = new MgmtViewNew($this);
		$addview->toFile();
		$indexview = new MgmtViewIndex($this);
		$indexview->toFile();
		$indexview = new MgmtViewClean($this);
		$indexview->toFile();
                $viewview = new MgmtViewView($this);
		$viewview->toFile();
	}
	
	public function delete()
	{
		$model = new MgmtModel($this);
		$model->delete();
		$model1 = new MgmtController($this);
		$model1->delete();
		$model2 = new MgmtViewIndex($this);
		$model2->delete();
		$model3 = new MgmtViewClean($this);
		$model3->delete();
		$model4 = new MgmtViewView($this);
		$model4->delete();
		$model5 = new MgmtViewNew($this);
		$model5->delete();
		$model6 = new MgmtViewFolder($this);
		$model6->delete();
	}
	
	public function getComment()
	{
		if(strlen($this->comment) > 0)
		{
			return " COMMENT='".$this->comment."'";
		}
	}
}

?>