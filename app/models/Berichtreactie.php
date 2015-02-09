<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Berichtreactie extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("berichtid", "Bericht", "id"); 
						 $this->hasOne("userid", "User", "id"); 
						 $this->hasOne("parentid", "Parent", "id"); 
						if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
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
	* @var varchar(36)
	*/
	public $berichtid;

	/*
	*
	*
	* @var varchar(36)
	*/
	public $userid;

	/*
	*
	*
	* @var text
	*/
	public $bericht;

	/*
	*
	*
	* @var varchar(36)
	*/
	public $parentid;

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

	/*
	*
	*
	* @var int(11)
	*/
	public $nummer;

	public $level;
	
	public $parentnum;
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
		  'field' => 'nummer',
		  'message' => 'Dit nummer is al een keer gebruikt'
		)));
		return $this->getMessages();
	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
	'id' => 'id','berichtid' => 'berichtid','userid' => 'userid','bericht' => 'bericht','parentid' => 'parentid','creationdate' => 'creationdate','lastedit' => 'lastedit','nummer' => 'nummer','level' => 'level','parentnum' => 'parentnum'
        );
    }
}
