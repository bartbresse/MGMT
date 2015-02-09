<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Tag extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		$this->hasMany("id", "BerichtTag", "tagid"); 
		$this->hasMany("id", "EventTag", "tagid"); 
		$this->hasOne("id", "", "id"); 
		if(strlen($this->id) < 8)
		{ 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		}
		$this->hasOne("userid", "User", "id"); 

		if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
		{ 
			$this->lastedit =  new Phalcon\Db\RawValue('now()');
		}
		if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
		{ 
			$this->creationdate =  new Phalcon\Db\RawValue('now()');
		} 
		$this->hasOne("entityid", "Entity", "id"); 				

		$this->hasMany("id", "CategoryTag", "tagid"); 
	}

		public function afterFetch()
		{
			$this->lastedit = date('H:i:s d-m-Y',strtotime($this->lastedit));
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
		  public $userid;
		
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
		  public $entityid;
		
		 /*
		  *
		  *
		  * @var varchar(255)
		  */
		  public $entity;
				
				
	public function gettag()
	{
		$slug = $this->slug;
		if($slug)
		{
			//get entity
			$category = Category::findFirst('slug = "'.$slug.'"');
			if($category)
			{
				return ''.$category->slug.'/';
			}
			
			$event = Event::findFirst('slug = "'.$slug.'"');
			if($event)
			{
				return 'agenda/'.$event->slug.'/';
			}
			
			$doelgroep = Doelgroep::findFirst('slug = "'.$slug.'"');
			if($doelgroep)
			{
				return 'doelgroep/'.$doelgroep->slug.'/';
			}
			
			$workshop = Workshop::findFirst('slug = "'.$slug.'"');
			if($workshop)
			{ 
				return 'workshops/'.$workshop->slug.'/';
			}
		}
	} 

	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'titel',
			  'message' => 'Deze titel is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'slug',
			  'message' => 'Deze titel is al een keer gebruikt'
			)));
	
		return $this->getMessages();
	
	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','titel' => 'titel','slug' => 'slug','userid' => 'userid','lastedit' => 'lastedit','creationdate' => 'creationdate','entityid' => 'entityid','entity' => 'entity'
        );
    }
}
