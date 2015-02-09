<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class MgmtEntitycolumn extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		$this->hasOne("id", "", "id"); 
		if(strlen($this->id) < 8)
		{ 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		}
	}
	
	/*
	*
	*
	* @var varchar(36)
	*/
	public $id;

	public $mgmtentityid;
	
	public $phalcontype;
	
	public $mgmttype;
	
	public $show;
	
	public $name;
	public $alias;
	
	public $type;
	public $length;
	public $default;
	public $null;
	public $unique;
	public $comments;
	
	/*
	*
	*
	* @var varchar(255)
	*/
	public function validation()
	{
	
	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array('id'=>'id','mgmtentityid'=>'mgmtentityid','phalcontype'=>'phalcontype','mgmttype'=>'mgmttype',
					'show'=>'show','name' => 'name','alias' => 'alias','type' => 'type','length' => 'length','default' => 'default',
					'null' => 'null','unique' => 'unique','comments' => 'comments');
    }
}
