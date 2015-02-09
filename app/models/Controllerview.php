<?php

class Controllerview extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 if(strlen($this->id) < 8 || $this->id == '0000-00-00')
		 { 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		 } 
		$this->hasOne("controllerid", "Controller", "id");
		$this->hasMany('id','Entity','viewid');	
	}

	public function beforeSave()
    {
        $this->entities = serialize($this->entities);
    }

    public function afterFetch()
    {
        $this->entities = unserialize($this->entities);
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
	* @var varchar(36)
	*/
	public $controllerid;

	/*
	*
	*
	* @var varchar(255)
	*/
	public $title;

	/*
	*
	*
	* @var text
	*/
	public $entities;
	/*  int(1)  */
	
	public $lastedit;
	
	public $struct;
	
	public $login;
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array('id' => 'id','controllerid' => 'controllerid','title' => 'title','entities' => 'entities','struct' => 'struct','login' => 'login','lastedit' => 'lastedit');
    }
}
