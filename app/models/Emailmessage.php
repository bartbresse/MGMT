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
	
		$this->hasOne("messageid", "Message", "id"); 
		
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

	/*
	*
	*
	* @var text
	*/
	public $tags;

	/*
	*
	*
	* @var varchar(255)
	*/
	public $mandrillid;

	/*
	*
	*
	* @var varchar(36)
	*/
	public $messageid;

	/*
	*
	*
	* @var varchar(36)
	*/
				  
	public function tags()
	{
		
	}			

	public $userid;
				  
	public function info()
	{
		if(strlen($this->mandrillid) > 2)
		{
			$di = \Phalcon\DI::getDefault();
			$mandrill = $di->getShared('mandrill');
			return $mandrill->messages_info($this->mandrillid);
		}
	}
				  
	public function send($to,$c = 0)
	{
		$adres = array();
		if($c = 0)
		{
			foreach($to as $tos)
			{
				array_push($adres,array('email' => $tos));
			}
		}
		else if($c = 1)
		{
			$adres = $to;
		}

		$email = array(
			'html'       => $this->html,
			'subject'    => $this->subject,
			'from_email' => $this->from_email,
			'from_name'  => $this->from_name,
			'to' => $adres
		);

		$di = \Phalcon\DI::getDefault();
		$mandrill = $di->getShared('mandrill');
		$result = $mandrill->messages_send($email);
		
		
		$this->mandrillid = $result[0]['_id'];
		
		return true;
	}			

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
			'id' => 'id','html' => 'html','subject' => 'subject','from_email' => 'from_email','from_name' => 'from_name',
			'to' => 'to','tags' => 'tags','mandrillid' => 'mandrillid','messageid' => 'messageid','tags' => 'tags',
			'userid' => 'userid'
        );
    }
}
