<?php

class Controllerview extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 if(strlen($this->id) < 8)
		 { 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		 } 
		
	}

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array();
    }
}
