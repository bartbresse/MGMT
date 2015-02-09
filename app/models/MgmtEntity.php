<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class MgmtEntity extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 }
	}

	public function afterFetch()
    {}

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
				  public $titel;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $slug;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $alias;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $clearance;
				
				/*
				  *
				  *
				  * @var int(1)
				  */
				  public $visible;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $newedit;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $view;
				
				public $orderentity;
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'id',
			  'message' => 'Deze id is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'titel',
			  'message' => 'Dit titel is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'slug',
			  'message' => 'Dit slug is al een keer gebruikt'
			)));
		
		return $this->getMessages();
	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','titel' => 'titel','slug' => 'slug','alias' => 'alias','clearance' => 'clearance','visible' => 'visible','newedit' => 'newedit','view' => 'view','orderentity' => 'orderentity'
        );
    }
}
