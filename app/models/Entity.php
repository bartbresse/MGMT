<?php

class Entity extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 if(strlen($this->id) < 8)
		 { 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		 } 
		$this->hasOne("viewid", "Controllerview", "id");
		
			
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
	  * @var varchar(36)
	  */
	  public $viewid;
	 /*
	  *
	  *
	  * @var varchar(36)
	  */
	  public $title;	
	 /*
	  *
	  *
	  * @var int(1)
	  */
	  public $single;
	 /*
	  *
	  *
	  * @var text
	  */
	  public $args;	
		
	
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array('id' => 'id','viewid' => 'viewid','title' => 'title','single' => 'single','args' => 'args');
    }
}
