<?php
class MgmtRelation extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
    }

    public $id;

    public $entityid;

    public $fromid;

    public $entityname;

    public $fromname;

    public $toid;

    public $relationtype;  

    public $null;

    /**
    * Independent Column Mapping.
    */
    public function columnMap()
    {
        return array("id" => "id","fromid" => "fromid","toid" => "toid","fromname" => "fromname","toname" => "toname","relationtype" => "relationtype","null" => "null");
    }
}
?>
