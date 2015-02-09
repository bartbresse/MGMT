<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Emailmessage extends \Phalcon\Mvc\Model
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
				  * @var text
				  */
				  public $html;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $subject;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $from_email;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $from_name;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $to;
				
				
				
					//TODO ADD THESE VARIABLES TO DB
				  public $tags;
				  
				  
	

	public function validation()
	{
		
		return $this->getMessages();

	}
	
	
	public function getinfo()
	{
		
	}
	

 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','html' => 'html','subject' => 'subject','from_email' => 'from_email','from_name' => 'from_name','to' => 'to'
        );
    }
}
