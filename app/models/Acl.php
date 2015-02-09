<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Acl extends \Phalcon\Mvc\Model
{
	public function initialize()
    {
		$this->hasOne("userid", "User", "id");
		if(strlen($this->id) < 8 || $this->id == '0000-00-00')
		{ 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		} 
	}


    /**
     *
     * @var string
     */
    public $id;
     
    /**
     *
     * @var string
     */
    public $entity;
    
	public $entityid; 
	 
    /**
     *
     * @var string
     */
    public $args;
     
    /**
     *
     * @var string
     */
    public $userid;
     
    /**
     *
     * @var integer
     */
    public $end;
     
    /**
     *
     * @var integer
     */
    public $request;   
    /**
     *
     * @var integer
     */
    public $actie;    

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'entity' => 'entity', 
            'args' => 'args', 
            'userid' => 'userid',
            'clearance' => 'clearance',			
            'end' => 'end',
            'request' => 'request',
            'actie' => 'actie',
			'entityid' => 'entityid'	
        );
    }

}
