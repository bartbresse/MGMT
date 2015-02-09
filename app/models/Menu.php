<?php

class Menu extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}

	public $tables;

	private function columnlist()
	{
		$cols = array();
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			array_push($cols,$column['Field']);
		}
		return $cols;
	}
	

	private function getmenuitem()
	{
		$notables = array('userinfo','user');
		$str = '';
		$tables = $this->tables;
		foreach($tables as $table)
		{
			if(!in_array($table,$notables))
			{
				$str .= '<li class="panel">
							<a class="accordion-toggle collapsed" data-toggle="collapse"
							   data-parent="#side-nav" href="#'.$table.'-collapse"><i class="fa fa-table"></i> <span class="name">'.ucfirst($table).'</span></a>
							<ul id="'.$table.'-collapse" class="panel-collapse collapse">
								<li><a href="<?php echo  $this->url->get(); ?>'.$table.'/">Overzicht</a></li>
								<li><a href="<?php echo  $this->url->get(); ?>'.$table.'/new">Nieuw</a></li>
							</ul>
						</li> ';
			}
		}
		return $str;
	}	


	private function getmenuitems()
	{
		$str = '';
		$tables = $this->tables;
		foreach($tables as $table)
		{
			$str .= '<ul class="nav navbar-nav">
						<li class="dropdown">
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.ucfirst($table).'<b class="caret"></b></a>
						  <ul class="dropdown-menu">
							<li><?php echo $this->tag->linkTo(array("'.$table.'/index", "Overview")); ?></li>
							<li><?php echo $this->tag->linkTo(array("'.$table.'/new", "New view")); ?></li>
						  </ul>
						</li>
					</ul>';

		}		
		return $str;
	}	

	public function tofile()
	{
		//#menuitems#
		
		$contents =	file_get_contents(BASEURL.'backend/templates/entity.rsi');
	//	$a = array('#menuitem#');
	//	$b = array($this->getmenuitem(),$this->getmenufunction());
		$model = str_replace($a,$b,$contents);
		$file = '../app/views/layouts/entity.volt';
		if(strlen($model) > 10){ file_put_contents($file,$model); } else{ return false; }
		return true;

		/*	//#menuitems#
			$contents =	file_get_contents('http://localhost/MGMT/backend/templates/menu.rsi');
			$a = array('#menuitems#');
			$b = array($this->getmenuitems());
			$model = str_replace($a,$b,$contents);
			$file = '../app/views/layouts/main.volt';
			if(strlen($model) > 10){ file_put_contents($file,$model); } else{ die('nomenufile'); } */
	}  

    public function columnMap(){ return array(); }
}
