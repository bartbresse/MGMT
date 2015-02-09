<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Vragen extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id");if(strlen($this->id) < 8 || $this->id == '0000-00-00')
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("userid", "User", "id");if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
						 { 
							$this->creationdate =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
						 { 
							$this->lastedit =  new Phalcon\Db\RawValue('now()');
						 }
	}

		public function afterFetch()
    {$this->creationdate = date('H:i:s d-m-Y',strtotime($this->creationdate));
$this->lastedit = date('H:i:s d-m-Y',strtotime($this->lastedit));
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
				  * @var text
				  */
				  public $vraag;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $antwoord;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $userid;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $creationdate;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $lastedit;
				
				
	

	public function validation()
				{
					
					return $this->getMessages();

				}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','vraag' => 'vraag','antwoord' => 'antwoord','userid' => 'userid','creationdate' => 'creationdate','lastedit' => 'lastedit'
        );
    }
}
