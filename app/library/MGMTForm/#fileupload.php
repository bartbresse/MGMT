<?php

namespace Phalcon\Forms;

class FileUpload extends Element implements ElementInterface
{
	public function  __construct(string $name, $options, array $attributes)
	{

	}
	
	public function render(array $attributes)
	{
		return 'my form element html'; 
	}
}

?>


/*
	public function  setForm($form)
	{
	
	}
	
	public function  getForm ()
	{
	
	}
	
	public function setName ($name)
	{
	
	}
	
	public function getName ()
	{
	
	}
	
	public function setFilters ($filters)
	{
	
	}
	
	public function addFilter ( $filter)
	{
	
	}
	
	public function getFilters ()
	{
	
	}
	
	/*
	public function addValidators ($validators,$merge){}
	public function addValidator ($validator){}
	public function getValidators (){}
	public function prepareAttributes ($attributes,$useChecked){}
	public function setAttribute ($attribute,$value){}
	public function getAttribute ($attribute,$defaultValue){}
	public function setAttributes ($attributes){}
	public function getAttributes (){}
	public function setUserOption ($option,$value){}
	public function getUserOption($option,$defaultValue){	}
	public function setUserOptions($options){	}
	public function getUserOptions (){	}
	public function setLabel ($label){	}
	public function getLabel (){	}
	public function label ($attributes){	}
	public function setDefault ($value){	}
	public function getDefault (){	}
	public function getValue (){	}
	public function getMessages (){	}
	public function hasMessages (){	}
	public function setMessages ($group){}
	public function appendMessage ($message){	}
	public function clear (){}
	public function __toString (){	}
	*/

?>