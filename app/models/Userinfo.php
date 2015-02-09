<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Userinfo extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("userid", "User", "id"); 
						
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
				  public $userid;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $httpreferer;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $remoteaddr;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $httpuseragent;
				
				
	

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
			'id' => 'id','userid' => 'userid','httpreferer' => 'httpreferer','remoteaddr' => 'remoteaddr','httpuseragent' => 'httpuseragent'
        );
    }
}
