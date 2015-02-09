<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class MgmtColumn extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
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
	
	public $length;
	public $bindinglength;
	public $show;
	
	public $nullcolumn;
	public $primarycolumn;
	public $uniquecolumn;
	public $creationdate;
	public $bindingtitle;
	public $bindingtype;
	public $connectingcolumn;
			
	public $default;
	public $comments;			
	public $phalcontype;		
			
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
			'id' => 'id','titel' => 'titel','type' => 'type','creationdate' => 'creationdate','nullcolumn' => 'nullcolumn','primarycolumn' => 'primarycolumn','uniquecolumn' => 'uniquecolumn','bindingtitle' => 'bindingtitle',
				'connectingcolumn' => 'connectingcolumn','length' => 'length','bindingtype' => 'bindingtype','default' => 'default','comments' => 'comments','show' => 'show','phalcontype' => 'phalcontype','bindinglength' => 'bindinglength'
        );
    }
}
