<?php
namespace MGMTForm;

interface IMGMTFormElement
{
	public function __construct($name,$view,array $attributes);
	
	public function getName();
	public function render();
	
}

class MGMTForm
{
	public $elements = array();

	public function add(IMGMTFormElement $element)
	{
		$this->elements[$element->getName()] = $element;
	}
	
	public function render($name)
	{
		return $this->elements[$name]->render();
	}
}

?>