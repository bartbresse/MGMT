<?php

class File extends \Phalcon\Mvc\Model
{
    /**
     * @var string
     */
    public $id;
	/**
	* @var string
	*/
	public $entityid; 
    /**
     * @var string
     */
    public $path;  
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $datetime;
    /**
     * @var string
     */
    public $thumb;
	
    /**
     * @var datetime
     */
    public $lastedit;
    /**
     * @var datetime
     */
    public $creationdate;	
	/*
	public function getpath()
	{
		$path = array_reverse(explode('../',$this->path));		
		return $path[0];
	}
	*/
	public function getbackendpath()
	{
		$path = array_reverse(explode('../',$this->path));		
		return '../../'.$path[0];
	}
	
	/*
	public function getThumb()
	{
		$path = array_reverse(explode('../',$this->path));	
		$tp = explode('/',$path[0]);
		$thumbpath = $tp[0].'/thumb/'.$tp[1];
		return $thumbpath;
	}*/
	
	public function getPath()
	{
		$path = array_reverse(explode('../',$this->path));		
		return '../../'.$path[0];
	}
	
	public function getThumb($type)
	{
		$thumb = MgmtThumb::findFirst('titel = "'.$type.'"');
		$epath = array_reverse(explode('/',$this->path));
		$file = $epath[0];
		$path =  explode($file,$this->path);
		return $path[0].$thumb->x.'/'.$thumb->y.'/'.$file;
	}
	
	/**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array('id' => 'id',
					'entityid' => 'entityid',
					'path' => 'path',
					'type' => 'type',
					'datetime' => 'datetime',
					'thumb' => 'thumb',
					'lastedit' => 'lastedit',
					'creationdate' => 'creationdate');
    }
}

?>
