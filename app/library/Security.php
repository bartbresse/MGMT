<?php


class Security extends Phalcon\Mvc\User\Component
{
	public function add()
	{
		//6c488ea3-b98d-11e3-8aab-0800270d665a
		$permission = Acl::findFirst();
		echo $permission->id;
	}
	
	public function remove()
	{

	}

	public function authenticate()
	{

	}
}
?>
