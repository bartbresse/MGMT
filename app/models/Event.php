<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Event extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasMany("id", "EventTag", "eventid"); 
							 $this->hasMany("id", "EventUser", "eventid"); 
							 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("userid", "User", "id"); 
						if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
						 { 
							$this->creationdate =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
						 { 
							$this->lastedit =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->start) < 8 || $this->start == '0000-00-00')
						 { 
							$this->start =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->einde) < 8 || $this->einde == '0000-00-00')
						 { 
							$this->einde =  new Phalcon\Db\RawValue('now()');
						 } $this->hasOne("fileid", "File", "id"); 
						 $this->hasOne("categoryid", "Category", "id"); 
						
	}

		public function afterFetch()
    {$this->creationdate = date('H:i:s d-m-Y',strtotime($this->creationdate));
$this->lastedit = date('H:i:s d-m-Y',strtotime($this->lastedit));
$this->start = date('H:i:s d-m-Y',strtotime($this->start));
$this->einde = date('H:i:s d-m-Y',strtotime($this->einde));
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
				  public $titel;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $beschrijving;
				
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
				  public $slug;
				
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
				  * @var datetime
				  */
				  public $start;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $einde;
				
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
				  public $category;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $categoryid;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $locatie;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $aanmeldingen;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $status;
				
				
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'titel',
			  'message' => 'Dit titel is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'slug',
			  'message' => 'Dit slug is al een keer gebruikt'
			)));
			
		return $this->getMessages();

	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','titel' => 'titel','beschrijving' => 'beschrijving','userid' => 'userid','slug' => 'slug','creationdate' => 'creationdate','lastedit' => 'lastedit','start' => 'start','einde' => 'einde','fileid' => 'fileid','category' => 'category','categoryid' => 'categoryid','locatie' => 'locatie','aanmeldingen' => 'aanmeldingen','status' => 'status'
        );
    }
}
