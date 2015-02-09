<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class CategoryTag extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
		 if(strlen($this->id) < 8)
		 { 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		 } 
		 
		 $this->hasOne("categoryid", "Category", "id"); 
		 $this->hasOne("tagid", "Tag", "id"); 
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
	  * @var varchar(36)
	  */
	  public $categoryid;
	
	/*
	  *
	  *
	  * @var varchar(36)
	  */
	  public $tagid;
				
				
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
				  'field' => 'id',
				  'message' => 'Deze id is al een keer gebruikt'
				)));
			
		
		return $this->getMessages();

	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','categoryid' => 'categoryid','tagid' => 'tagid'
        );
    }
}
