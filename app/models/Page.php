<?php



class Page extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	//complete controller info
	public $page;
	//only this view
	public $view;

	public $folder;

	public $entities;
	
	//generates php from entity property array
	private function getentities()
	{
		$str = '';
		$ett = array();
		foreach($this->entities as $entity)
		{
			if(!isset($ett[$entity['entity']])){ $ett[$entity['entity']] = $entity['single']; }									
		}
	


		$keys = array_keys($ett);
		if(count($keys) > 0)
		{
			for($i=0;$i<count($keys);$i++)
			{
				if($ett[$keys[$i]] == 0)
				{
					$str .= 'foreach($'.$keys[$i].'s as $'.$keys[$i].'){
';
					foreach($this->entities as $entity)
					{
						if($entity['entity'] == $keys[$i])
						{
							$str .= ''.$entity['str'].'';
						}
					}
					$str .= '}
';				
				}	
				else
				{
					$str .= '
';
					foreach($this->entities as $entity)
					{
					
						if($entity['entity'] == $keys[$i])
						{
							$str .= ' '.$entity['str'].'';
						}
					}
					$str .= '
';
				}	
			}
		}
		return $str;
	}
	
	public function tofile()
	{
		$contents =	file_get_contents(BASEURL.'backend/templates/page.rsi');
		$a = array('#entities#');
		$b = array($this->getentities());
		$model = str_replace($a,$b,$contents);
		$file = '../../app/views/'.$this->folder.'/'.$this->view->title.'.volt';
		
		if(strlen($model) > 1)
		{ 
			file_put_contents($file,$model); 
			chmod($file,0777); 
		}
		else
		{ 
			return false; 
		}
	}  

    public function columnMap(){ return array(); }
}
