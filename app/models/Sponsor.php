<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Sponsor extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("fileid", "File", "id"); 
						 $this->hasOne("userid", "User", "id"); 
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
				  * @var varchar(255)
				  */
				  public $titel;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $slug;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $url;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $fileid;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $beschrijving;
				
				/*
				  *
				  *
				  * @var varchar(255)
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
				{$this->validate(new Uniqueness(array(
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
			'id' => 'id','titel' => 'titel','slug' => 'slug','url' => 'url','fileid' => 'fileid','beschrijving' => 'beschrijving','userid' => 'userid','creationdate' => 'creationdate','lastedit' => 'lastedit'
        );
    }
}
