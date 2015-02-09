<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class User extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasMany("id", "Acl", "userid"); 
							 $this->hasMany("id", "Bericht", "userid"); 
							 $this->hasMany("id", "Berichtreactie", "userid"); 
							 $this->hasMany("id", "Category", "userid"); 
							 $this->hasMany("id", "Contact", "userid"); 
							 $this->hasMany("id", "Doelgroep", "userid"); 
							 $this->hasMany("id", "Event", "userid"); 
							 $this->hasMany("id", "EventUser", "userid"); 
							 $this->hasMany("id", "Pagina", "userid"); 
							 $this->hasMany("id", "Sponsor", "userid"); 
							 $this->hasMany("id", "Tag", "userid"); 
							 $this->hasMany("id", "Userinfo", "userid"); 
							 $this->hasMany("id", "Vraag", "userid"); 
							 $this->hasMany("id", "Workshop", "userid"); 
							 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("fileid", "File", "id"); 
						
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
				  public $firstname;
				public $mobile;
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $insertion;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $lastname;
				
				/*
				  *
				  *
				  * @var tinyint(3)
				  */
				  public $status;
				
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
				  public $password;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $clearance;
				
				/*
				  *
				  *
				  * @var varchar(35)
				  */
				  public $postcode;
				
				/*
				  *
				  *
				  * @var varchar(35)
				  */
				  public $city;
				
				/*
				  *
				  *
				  * @var varchar(35)
				  */
				  public $street;
				
				/*
				  *
				  *
				  * @var varchar(35)
				  */
				  public $streetnumber;
				
				/*
				  *
				  *
				  * @var varchar(35)
				  */
				  public $telephone;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $fileid;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $verification;
			

			public $newsletter;
				
			public $orderrows;
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'id',
			  'message' => 'Deze id is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'email',
			  'message' => 'Deze email is al een keer gebruikt'
			)));
			
		return $this->getMessages();

	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','firstname' => 'firstname','insertion' => 'insertion','lastname' => 'lastname','status' => 'status','email' => 'email','password' => 'password','clearance' => 'clearance','postcode' => 'postcode','city' => 'city','street' => 'street','streetnumber' => 'streetnumber','telephone' => 'telephone','mobile' => 'mobile','fileid' => 'fileid','verification' => 'verification','newsletter'=>'newsletter','orderrows' => 'orderrows'
        );
    }
}
