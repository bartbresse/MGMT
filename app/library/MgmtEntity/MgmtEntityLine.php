<?php
namespace MgmtEntity;

class MgmtEntityLine
{
	public $mgmttype;
	public $name;
	public $alias;
	public $type;
	public $length;
	public $default;
	public $null;
	public $comments;
	public $show;
	public $unique;
	public $phalcontype;
	
	public function getProperties()
	{
		return array('mgmttype','name','alias','type','length','default','null','comments','show','unique');
	}
	
	public function getNull()
	{
		if($this->null == 1)
		{
			return 'NULL';
		}
		else
		{
			return 'NOT NULL';
		}
	}
	
	public function getSQLComment()
	{
		if(strlen($this->comments) > 0)
		{
			return "COMMENT '".$this->comments."'";
		}
	}
	
	public function getDefault()
	{
		return $this->default;	
	}
	
	public function getTypeLength()
	{
		$typeswithlength = array('VARCHAR','INT','BOOLEAN');
	
		if($this->length > 0 && in_array(strtoupper($this->type),$typeswithlength))
		{
			return strtoupper($this->type).'('.$this->length.')';
		}
		else
		{
			return $this->type;
		}
	}
}

?>