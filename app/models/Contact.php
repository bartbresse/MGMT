<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Contact extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 }if(strlen($this->post) < 8 || $this->post == '0000-00-00')
						 { 
							$this->post =  new Phalcon\Db\RawValue('now()');
						 } $this->hasOne("userid", "User", "id"); 
						
	}

		public function afterFetch()
    {$this->post = date('H:i:s d-m-Y',strtotime($this->post));
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
				  public $naam;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $email;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $telefoon;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $bericht;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $post;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $userid;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $nieuwsbrief;
				
				
	

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
			'id' => 'id','naam' => 'naam','email' => 'email','telefoon' => 'telefoon','bericht' => 'bericht','post' => 'post','userid' => 'userid','nieuwsbrief' => 'nieuwsbrief'
        );
    }
}
