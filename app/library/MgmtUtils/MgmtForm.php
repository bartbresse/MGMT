<?php
namespace MgmtUtils;

class MgmtForm
{
	public $editable = false;


	public function editField($field,$value)
	{
		if($this->editable)
		{
			return '<div class="edit '.$field.'">'.$value.'</div>';
		}
		else
		{
			return $value;
		}
	}
	
	public function getAlias()
	{
	
	}
	
}