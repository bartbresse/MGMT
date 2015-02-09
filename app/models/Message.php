<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Message extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasMany("id", "Emailmessage", "messageid"); 
							 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 }if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
						 { 
							$this->lastedit =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
						 { 
							$this->creationdate =  new Phalcon\Db\RawValue('now()');
						 } $this->hasOne("templateid", "Template", "id"); 
						
	}

		public function afterFetch()
    {$this->lastedit = date('H:i:s d-m-Y',strtotime($this->lastedit));
$this->creationdate = date('H:i:s d-m-Y',strtotime($this->creationdate));
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
				  public $subject;
				
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
				  public $to;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $bcc;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $footer;
				
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
				  public $creationdate;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $templateid;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $tags;
				
				
	

	public function validation()
				{$this->validate(new Uniqueness(array(
							  'field' => 'id',
							  'message' => 'Deze id is al een keer gebruikt'
							)));
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
			'id' => 'id','titel' => 'titel','slug' => 'slug','subject' => 'subject','html' => 'html','to' => 'to','bcc' => 'bcc','footer' => 'footer','lastedit' => 'lastedit','creationdate' => 'creationdate','templateid' => 'templateid','tags' => 'tags'
        );
    }
}
