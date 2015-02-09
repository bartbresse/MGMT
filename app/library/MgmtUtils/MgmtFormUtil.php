<?php
namespace MgmtUtils;

class MgmtFormUtil
{
	private $mgmtentity;
	private $entity;

	public function __construct($entity,$mgmtentity)
	{
		$this->entity = $entity;
		$this->mgmtentity = $mgmtentity;
	}
	

	public function getAlias($title)
	{
	//	$this->mgmtentity->mgmtentitycolumn::findFirst('name = "'..'"');
	}
}