<?php

class Frontendcontroller extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	//page array 
	public $controller;


	
	public function getpages()
	{
		$controller = $this->controller;
		$str =  '';
		foreach($controller->Controllerview as $view)
		{
			$str .= 'public function '.$view->title.'Action()
					{';
			
			foreach($view->Entity as $entity)
			{						
				if($entity->single == 1)
				{
					if($view->struct == 1)
					{
						$str .= ' $slug = $this->request->getQuery(\'slug\'); 
						';
					}
					$str .= '
							$'.$entity->title.'s = '.ucfirst($entity->title).'::findFirst('.$entity->args.');
							$this->view->setVar("'.$entity->title.'s",$'.$entity->title.'s);
							';
				}
				else
				{
					$str .= '
							$'.$entity->title.'s = '.ucfirst($entity->title).'::find('.$entity->args.');
							$this->view->setVar("'.$entity->title.'s",$'.$entity->title.'s);
							';
				}
			}
			$str .= '}
				
					';			
					
			if($controller->title == 'index')
			{
				$str .= '
						public function route404Action()
						{

						}

						'; 
			}			
		}
		return $str;		
	}

	public function tofile()
	{
		$contents =	file_get_contents(BASEURL.'backend/templates/frontendcontroller.rsi');
		$controller = $this->controller;

		$a = array('#Entity#','#pages#');
		$b = array(ucfirst($controller->controllername),$this->getpages());

		$model = str_replace($a,$b,$contents);
		$file = '../../app/controllers/'.ucfirst($this->controller->controllername).'Controller.php';
		if(strlen($model) > 10){ file_put_contents($file,$model); 

		//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
		chmod($file,0777); 

		} else{ die('nocontrollerfiles'); }
	}  

    public function columnMap(){ return array(); }
}
