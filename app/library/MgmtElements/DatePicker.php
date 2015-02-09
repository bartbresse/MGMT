<?php
namespace Phalcon\Forms;

use Phalcon\Mvc\Model\Resultset;

class DatePicker extends Element implements ElementInterface
{
	private $name;
	private $form;
	
    public function  __construct($name, $options = false)
    {
		$this->name = $name;
		if(isset($options['id'])){ $this->id = $options['id']; }else{ $this->id = ''; }
		if(isset($options['class'])){ $this->class = $options['class']; }else{ $this->class = '';}
		if(isset($options['value'])){  }else{ $this->value = ''; }
		if(isset($options['placeholder'])){ $this->placeholder = $options['placeholder']; }else{ $this->placeholder = ''; }
		if(isset($options['x'])){ $this->x = $options['x']; }else{ $this->x = ''; }
		if(isset($options['y'])){ $this->y = $options['y']; }else{ $this->y = ''; }
		return 'FileUPload';
    }
	
	public function setName ($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	private function getHtml()
	{
		$html = '<input id="'.$this->id.'" class="'.$this->class.'" type="text" name="'.$this->id.'"><script>$(\'#'.$this->id.'\').datetimepicker();</script>';
		return $html;
	}
	
    public function render($attributes = false)
	{
		return $this->getHtml();
	}
	
	public function getForm ()
	{
		return $this->form;
	}
	
	public function setForm ($form)
	{
		$this->form = $form;
	}
	
	public function getValue ()
	{
		return 'value';
	}
	
	public function setDefault ($value)
	{
		
	}

	public function getLabel()
	{
		return 'label';
	}
	
}

?>