<?php

class Column extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	public function beforeValidationOnCreate()
    {
		 if(strlen($this->id) < 8)
		 { 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		 }
    }
	
	 /*
	  *
	  *
	  * @var varchar(36)
	  */
	  public $id;
	 /*
	  *
	  *
	  * @var varchar(255)
	  */
	  public $entity;	
	 /*
	  *
	  *
	  * @var varchar(255)
	  */
	  public $columns;
	 /*
	  *
	  *
	  * @var varchar(255)
	  */
	  public $order;
	  /*
	  *
	  *
	  * @var varchar(255)
	  */
	  public $records;

	public $orderedcolumns;  
	
	public function ordercolumns()
	{
		$columns = $this->columns;
		$order = $this->order;
		if(is_array($order) && count($order) > 0)
		{
			$orderedcolumns = array();	
			foreach($order as $ordered)
			{
				if(in_array($ordered,$columns))
				{
					array_push($orderedcolumns,$ordered);
				}
			}
			$this->orderedcolumns = $orderedcolumns;
		}
		else
		{
			$this->orderedcolumns = $columns;
		}
	}	
		
	public function getallorderedcolumns($columns)
	{
		$order = $this->order;
		if(count($columns) == count($order))
		{
			return $order;
		}
		return $columns;
	}	
			
	public function beforeSave()
	{
		$this->order = serialize ( $this->order );
		$this->columns = serialize ( $this->columns );
	}

	public function afterFetch()
	{
		$this->order = unserialize ( $this->order );
		$this->columns = unserialize ( $this->columns );
		$this->ordercolumns();
	}		

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array('id' => 'id','entity' => 'entity','columns' => 'columns','order' => 'order','records' => 'records');
    }
}
?>
