<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Pagina extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasMany("id", "Category", "paginaid"); 
							 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("userid", "User", "id"); 
						 $this->hasOne("fileid", "File", "id"); 
						if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
						 { 
							$this->creationdate =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
						 { 
							$this->lastedit =  new Phalcon\Db\RawValue('now()');
						 } $this->hasOne("categoryid", "Category", "id"); 
						 $this->hasOne("parentid", "Parent", "id"); 
						
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
				  * @var varchar(36)
				  */
				  public $fileid;
				
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
				  * @var varchar(36)
				  */
				  public $categoryid;
				
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
				  public $parentid;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $niveau;
				
				
	

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
			'id' => 'id','titel' => 'titel','slug' => 'slug','beschrijving' => 'beschrijving','userid' => 'userid','fileid' => 'fileid','creationdate' => 'creationdate','lastedit' => 'lastedit','categoryid' => 'categoryid','url' => 'url','parentid' => 'parentid','niveau' => 'niveau'
        );
    }
}
