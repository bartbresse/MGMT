<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class EventTag extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("eventid", "Event", "id"); 
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
				  public $eventid;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $tagid;
				
				
	

	public function validation()
				{$this->validate(new Uniqueness(array(
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
			'id' => 'id','eventid' => 'eventid','tagid' => 'tagid'
        );
    }
}
