<?php
namespace MGMTForm;

class FileUpload implements IMGMTFormElement 
{
	private $name;
	private $html;
	
	private function getHtml()
	{
		
	}
	
	public function __construct($name,$view,array $attributes)
	{
		$this->name = $name;
		$a = $attributes;
		if(!isset($a['slot']) || !strlen($a['slot']) > 0){ $a['slot'] = 0; }
		if(!isset($a['x']) || !strlen($a['x']) > 0){ $a['x'] = 0; }
		if(!isset($a['y']) || !strlen($a['y']) > 0){ $a['y'] = 210; }
		if(!isset($a['cx']) || !strlen($a['cx']) > 0){ $a['cx'] = 210; }
		if(!isset($a['cy']) || !strlen($a['cy']) > 0){ $a['cy'] = 200; }
		if(!isset($a['id']) || !strlen($a['id']) > 0){ $a['id'] = 23; }
		if(!isset($a['crop']) || !strlen($a['crop']) > 0){ $a['crop'] = "true"; }
				
		$partial = $this->getHtml();
		$this->html = '<div style="margin-left:15px;">
				<?php
					$config = array("slot" => '.$a['slot'].',"x" => '.$a['x'].',"y" => '.$a['y'].',"cx" => '.$a['cx'].',"cy" => '.$a['cy'].',"id" => '.$a['id'].',"crop" => '.$a['crop'].');	
					$this->partial("file/singleupload"); 
				?>
				</div>';
		return $this->html;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function render()
	{
		return $this->html;
	}
}

?>