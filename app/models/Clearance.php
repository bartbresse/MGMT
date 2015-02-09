<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Clearance extends \Phalcon\Mvc\Model
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
				  * @var int(11)
				  */
				  public $value;
				
				
	

	public function validation()
				{$this->validate(new Uniqueness(array(
							  'field' => 'titel',
							  'message' => 'Dit titel is al een keer gebruikt'
							)));
						
					
					return $this->getMessages();

				}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','titel' => 'titel','value' => 'value'
        );
    }
}
