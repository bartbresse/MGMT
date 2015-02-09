<?php

class Frontendmenu extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	public $controllers;


	private function getentities()
	{
		$str = '';
		$view = Controllerview::findFirst('title = "HEADERFOOTER"');

		if(isset($view->Entity))
		{
			foreach($view->Entity as $entity)
			{
				$str .= '$'.$entity->title.'->';
			}
		}

		return $str;
	}
	
	private function getmenuitems()
	{
		$str = '<ul>';
		$controllers = $this->controllers;
		foreach($controllers as $controller)
		{
			$str .= '<li><a href="'.$controller->title.'"><?=$this->url->get(\''.$controller->controllername.'\');?></a></li>
					';
		}		
		$str .= '</ul>';

		return $str;
	}	

	public function tofile()
	{
		$contents =	file_get_contents(BASEURL.'backend/templates/frontendmenu.rsi');
		$a = array('#menuitems#','#entities#');
		$b = array($this->getmenuitems(),$this->getentities());

		$model = str_replace($a,$b,$contents);
		$file = '../../app/views/layouts/main.volt';

		if(strlen($model) > 10){ 
			file_put_contents($file,$model); 
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
			chmod($file,0777); 
		}else{ return false; }
	}  

    public function columnMap(){ return array(); }
}
