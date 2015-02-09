<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class MgmtEntity2 extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		$this->hasOne("id", "", "id"); 
		if(strlen($this->id) < 8)
		{ 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		}
		$this->hasMany("id","MgmtRelation","entityid"); 
		$this->hasMany("id","MgmtEntitycolumn","mgmtentityid");
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
	public $name;

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
	public $alias;

	/*
	*
	*
	* @var int(11)
	*/
	public $clearance;

	/*
	*
	*
	* @var int(1)
	*/
	public $visible;

	/*
	*
	*
	* @var varchar(255)
	*/
	public $newedit;

	/*
	*
	*
	* @var varchar(255)
	*/
	public $view;

	public $orderentity;
	public $entity;
	public $columns;
	public $order;
	public $records;
	public $viewid;
	public $args;
	public $single;
	
	public $entityorder;
	public $entityseperator;
	
	public $class;
	public $comment;
	public $newtext;
	
	public $model;
	
	public $showgrid;
	public $inlinedit;
	
	public $systementity;
	
	
	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'id',
			  'message' => 'Deze id is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'name',
			  'message' => 'Deze name is al een keer gebruikt'
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
			'id' => 'id','name' => 'name','slug' => 'slug','alias' => 'alias','clearance' => 'clearance','visible' => 'visible','newedit' => 'newedit','view' => 'view','orderentity' => 'orderentity',
			'entity' => 'entity','columns' => 'columns','order' => 'order','records' => 'records','viewid' => 'viewid','args' => 'args','single' => 'single','viewid' => 'viewid','entityorder' => 'entityorder',
			'entityseperator' => 'entityseperator','class' => 'class','comment' => 'comment','newtext' => 'newtext','module' => 'module','showgrid' => 'showgrid','inlineedit' => 'inlineedit','systementity'=>'systementity');
    }
}
