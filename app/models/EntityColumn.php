<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class MgmtLang extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 }
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
	public $type;

	/*
	*
	*
	* @var varchar(255)
	*/
	public $creationdate;

	public $nullcolumn;
	public $primarycolumn;
	public $uniquecolumn;
	  	
		
	public function translate($key)
	{
		$lang = $this::findFirst('key = "'.$key.'"');
		if($lang)
		{
			return ucfirst($lang->value);
		}
		else
		{
			return ucfirst($key);
		}
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
			'id' => 'id','titel' => 'titel','type' => 'type','creationdate' => 'creationdate','nullcolumn' => 'nullcolumn','primarycolumn' => 'primarycolumn','uniquecolumn' => 'uniquecolumn'
        );
    }
}
