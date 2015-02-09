<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Artikel extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
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
				  public $prijs;
				
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
			'id' => 'id','titel' => 'titel','slug' => 'slug','prijs' => 'prijs','url' => 'url','fileid' => 'fileid'
        );
    }
}
