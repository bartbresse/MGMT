<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Controller extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		if(strlen($this->id) < 8)
		{ 
			$this->id =  new Phalcon\Db\RawValue('uuid()');
		}
		$this->hasMany('id','Controllerview','controllerid');
	}

	public $language;
	//model name 
	public $entity;
	//model columns array
	public $columns;
	//tables 
	public $tables;
	//public relations
	public $relations;
	//link table relations
	public $linkrelations;	
	//determines wether there is a multiple file upload function or not //true or false
	public $images;
	//public relations
	public $controllername;
	//functions/widgets for new/edit 
	public $functions;
	
	//database columns
	   /*
		*  @var varchar(36)
		*/
	public $id;
	   /*
		*  @var varchar(255)
		*/
	public $title;

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
	
	private function getspecialfields()
	{
		$str = '';
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			$name = explode('id',$column['Field']);			
			if(!isset($name[1]))
			{
				if($column['Type'] == 'text')
				{
					$str .= '$form->add(new Textarea("'.$column['Field'].'", array("id" => "'.$column['Field'].'","class" => "form-control")));
							';	
				}
				if($column['Field'] == 'password')
				{
					$str .= '$form->add(new Password("password", array("id" => "password","class" => "form-control")));
							';	
					$str .= '$form->add(new Password("password2", array("id" => "password2","class" => "form-control")));
							';					
				}
				if($column['Field'] == 'url')
				{
					$str .= '$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://")));	';	
				}	
			}
			else
			{
				$nofields = array('id','userid','parentid','fileid');
				if($column['Field'] == 'parentid')
				{
					$str .= '
							$form->add(new Select("parentid",'.ucfirst($this->entity).'::find(),array(\'using\' => array(\'id\', \'titel\'),"id" => "parentid","class" => "form-control")));
							';
				}
				else if(!in_array($column['Field'],$nofields))
				{
					$rel = explode('id',$column['Field']);
					$foreign = $rel[0];
				/*	$str .= '	
							/*	$'.$rel[0].'s = '.ucfirst($rel[0]).'::find();
								$this->view->setVar("'.$rel[0].'s", $'.$rel[0].'s);*//*
								
								//special field line controller.php line:95
								$acls = $this->check("'.$rel[0].'");
								if(!isset($sessionuser)){ $sessionuser = $this->session->get(\'auth\'); }
								$resultset = '.ucfirst($rel[0]).'::find()->filter(function($e,$sessionuser,$acls){
									if(in_array($e->id,$acls) || $sessionuser["clearance"] >= 900)
									{   return $e;	}
								});
								$this->view->setVar("'.$rel[0].'s",$resultset);
								
							';*/
					
					if($foreign == 'user')
					{
						$field = 'category';
					}
					
					if($foreign == 'category')
					{
						$field = 'id';
					}
					
					if($foreign == 'tag')
					{
						$field = 'entityid';					
					}
			
					$str .= '	//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get(\'auth\'); }
								if($sessionuser[\'clearance\'] < 900)
								{
									$q = $this->orquery(\''.$field.'\',$this->check(\''.$foreign.'\'));
									$resultset = '.ucfirst($foreign).'::find(array(\'conditions\' => $q));
								}
								else
								{
									$resultset = '.ucfirst($foreign).'::find();
								}
								$this->view->setVar("'.$foreign.'s",$resultset);	
							';									
				}
			}
		}
		return $str;
	}


	private function getfilters()
	{
		$str = '';
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			$name = explode('id',$column['Field']);			
			if(!isset($name[1]))
			{
				switch($column['Type'])
				{
					case 'varchar(255)':
					case 'varchar(45)':
					case 'varchar(300)':
					case 'varchar(200)':
					case 'varchar(150)':
					case 'varchar(100)':
					case 'varchar(36)':
					case 'varchar(35)':
					case 'varchar(32)':
					case 'varchar(11)':
					case 'text':
						$type = 'string';						
					break;
					case 'int(11)':
					case 'int(3)':
					case 'tinyint(3)':
						$type = 'int';					
					break;
					case 'float':
						$type = 'float';					
					break;
					/*case 'datetime':
						$type = 'datetime';					
					break;//datetime is for some reason not supported
					default:
						echo $column['Type'];
						$type = 'not found';*/
					break;
				}
				if($column['Field'] == 'slug')
				{

				}
				else if($column['Field'] == 'password')
				{
				$str .= '$validation->setFilters("password", array("'.$type.'", "striptags"));
						';
				$str .= '$validation->setFilters("password2", array("'.$type.'", "striptags"));
						';		
				}
				else if(strlen($type) > 0)
				{

				$str .= '$validation->setFilters("'.$column['Field'].'", array("'.$type.'", "striptags"));	';

				}	$type ='';	
			}
		}
		return $str;
	}

	private function getchecks()
	{
		$str = '';
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			//if it has no id in column field check for presence					
			$name = explode('id',$column['Field']);			
					
			$novalidation = array('slug','creationdate','lastedit','fileid','userid');
			
			if($column['Type'] && !in_array($column['Field'],$novalidation))
			{			
				if(!isset($name[1]) && $column['Null'] == 'NO')
				{
					//changed variable for validation
					$str .= '
							$validation->add(\''.$column['Field'].'\', new PresenceOf(array(
								\'field\' => \'specificaties\',
								\'message\' => \'Het veld '.$column['Field'].' is verplicht.\'
							)));
							';
				}
			
				//if it is VARCHAR 255 or TEXT add minimal length check if column NOTNULL			
				if($column['Null'] == 'NO' && ($column['Type'] == 'varchar(255)' || $column['Type'] == 'text'))
				{
					//changed variable for validation
					$str .= '
							$validation->add(\''.$column['Field'].'\', new StringLength(array(
								\'messageMinimum\' => \'Vul een '.$column['Field'].' in van tenminste 2 characters.\',
								\'min\' => 2
							)));
							';
				}
			}		
		}
		return $str;
	}

	private function addcontroller()
	{
		$str = '';
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			//if there is a column fileid make sure files get linked to  it
			if($column['Field'] == 'fileid')
			{
				$str .= 'if(isset($post[\'files\'])){ $'.$this->entity.'->fileid = $post[\'files\'][0]; } ';
			}
			
			if($column['Type'] == 'datetime')
			{
				if($column['Field'] == 'creationdate')
				{
					//isset($post['id']) && strlen($post['id']) == 0
				
					$str .= 'if(!isset($'.$this->entity.'x->id)){ $'.$this->entity.'->creationdate = new Phalcon\Db\RawValue(\'now()\');  } 
							';
				}
				else if($column['Field'] == 'lastedit')
				{
					$str .= ' $'.$this->entity.'->lastedit = new Phalcon\Db\RawValue(\'now()\');   
							';
				}
				else
				{
					$str .= '$event->'.$column['Field'].' = date(\'Y-m-d H:i:s\',strtotime($post[\''.$column['Field'].'\']));
							';		
				}
			}
			
			if($column['Field'] == 'userid')
			{
				$str .= ' $'.$this->entity.'->userid = $this->user[\'id\'];  
						';
			}
			
			if($column['Field'] == 'slug')
			{
				//predefined fields for a slug
				$pos = array('title','titel','name','naam');
				$matches = array_intersect($pos,$this->columnlist());
				
				if(count($matches) > 0)
				{
					$value = array_shift(array_values($matches));
					$str .= '$'.$this->entity.'->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post[\''.$value.'\']));';
				}			
			}

			if($column['Field'] == 'password')
			{
				$str .= '  
						if(strlen($post[\'password\']) > 5 && strlen($post[\'password2\']) > 5)
						{
							if($post[\'password\'] == $post[\'password2\'])
							{
								$'.$this->entity.'->password = $this->security->hash($post[\'password\']);
							}
						}
						';
			}
		}
		
		$linkrelations = $this->linkrelations;
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
		
			if($rel[0] == $this->entity)
			{ $foreign = $rel[1]; }else{ $foreign = $rel[0]; }
						
			$str .= '	
					
					if(isset($post[\''.$foreign.'s\']) && is_array($post[\''.$foreign.'s\']))
					{
						//delete previous choices
						foreach('.ucfirst($rel[0]).ucfirst($rel[1]).'::find(\''.$rel[0].'id = "\'.$'.$rel[0].'->id.\'"\') as $'.$rel[0].$rel[1].')
						{	if($'.$rel[0].$rel[1].'->delete() == false)
							{ 	$message[\'MGMTSYSTEM\'] = \'SYSTEM ERROR: UNABLE TO DELETE ROW\';
								array_push($status[\'messages\'],$message);	}
						}		
					
					
						foreach($post[\''.$foreign.'s\'] as $'.$foreign.')
						{
							$'.$rel[0].$rel[1].' = '.ucfirst($rel[0]).ucfirst($rel[1]).'::findFirst(\''.$this->entity.'id = "\'.$'.$this->entity.'->id.\'" AND '.$foreign.'id = "\'.$'.$foreign.'.\'"\');
							if(!isset($'.$rel[0].$rel[1].'->id))
							{  
								$'.$rel[0].$rel[1].' = new '.ucfirst($rel[0]).ucfirst($rel[1]).'();	
								$'.$rel[0].$rel[1].'->id = new Phalcon\Db\RawValue(\'uuid()\');
								$'.$rel[0].$rel[1].'->'.$this->entity.'id = $'.$this->entity.'->id;
								$'.$rel[0].$rel[1].'->'.$foreign.'id = $'.$foreign.';
								if($'.$rel[0].$rel[1].'->save())
								{ }
								else
								{
									foreach ($'.$rel[0].$rel[1].'->getMessages() as $message)
									{
										//	echo $message;
									}
								}
							}		
						}
					}
					
					';
		}			
		return $str;
	}

	public function getsearchcolumns()
	{
		$str = '';
		$columns = $this->columns;
		$cc = 0;		
		foreach($columns as $column)
		{
			$name = explode('id',$column['Field']);			
			if(!isset($name[1]))
			{
				if($cc > 0){ $str .= ','; }				
				$str .= "'".$column['Field']."'";
				$cc++;			
			}
			
		}
		return $str;
	}

	public function getcolumns()
	{
		$str = '';
		$columns = $this->columns;
		$cc = 0;		
		foreach($columns as $column)
		{
			$name = explode('id',$column['Field']);			
			if(!isset($name[1]))
			{
				if($cc > 0){ $str .= ','; $this->firstcolumn = $column['Field']; }				
				$str .= "'".$column['Field']."'";
				$cc++;			
			}
		}
		return $str;
	} 

	private function gettabs()
	{
		$str = '';$cc =0;
		$relations = $this->relations;
		foreach($relations as $relation)
		{
			if($cc > 0){ $str .= ','; }
			$str .= "'".$relation."'";		
			$cc++;			
		}
		return $str;
	}
	
	/*	THIS FUNCTION LOADS ALL CONNECTED ENTITIES TO THIS ENTITY */
	private function getrelations()
	{
		//TODO implement difference between action for each entity
		$str = '';
		$relations = $this->relations;
		foreach($relations as $relation)
		{
			$str .= '
					//start '.$relation.' stuff 
					
					$actions = $this->actionauth(\''.$relation.'\');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst(\'entity = "'.$relation.'"\');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("'.$relation.'columns", $columns);	
					
					$allcolumns = array(\'titel\',\'slug\',\'lastedit\',\'creationdate\');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args[\'entity\'] = \''.ucfirst($relation).'\';
					$args[\'args\'] = \''.$this->entity.'id = "\'.$'.$this->entity.'->id.\'"\';
					$args[\'search\'] = false;
					
					$index'.$relation.'s = $this->search2($args); 		
					$this->view->setVar("index'.$relation.'s", $index'.$relation.'s);
					
					//end '.$relation.' stuff
					';
		}
		
		$linkrelations = $this->linkrelations;
		$cc=0;	
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
		
			if($rel[0] == $this->entity)
			{ $foreign = $rel[1]; }else{ $foreign = $rel[0]; }
				
			$str .= '
					
					//start '.$relation.' stuff 	
			
					$actions = $this->actionauth(\''.ucfirst($foreign).'\');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst(\'entity = "'.$foreign.'"\');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("'.$foreign.'columns", $columns);	
				
					$allcolumns = array(\'titel\',\'slug\',\'lastedit\',\'creationdate\');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args[\'entity\'] = \''.ucfirst($rel[0]).ucfirst($rel[1]).'\';
					$args[\'args\'] = \''.$this->entity.'id = "\'.$'.$this->entity.'->id.\'"\';
					$args[\'search\'] = false;
					$'.$rel[0].$rel[1].'s = $this->search2($args); 
					$index'.$foreign.'s->items = array();
				
					//relation to entity
					foreach($'.$rel[0].$rel[1].'s->items as $'.$rel[0].$rel[1].')
					{ $index'.$foreign.'s->items[] = $'.$rel[0].$rel[1].'->'.ucfirst($foreign).';	}
					$index'.$foreign.'s->total_pages = $'.$rel[0].$rel[1].'s->total_pages;

					$this->view->setVar("index'.$foreign.'s", $index'.$foreign.'s);
					
					//end '.$relation.' stuff
					';				
			$cc++;		
		}		
		return $str;
		
	}
	
	public function getlinkrelations()
	{
		$str = '';
		$linkrelations = $this->linkrelations;
		$cc=0;	
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
		
			if($rel[0] == $this->entity)
			{ $foreign = $rel[1]; }else{ $foreign = $rel[0]; }
	
			if($foreign == 'user')
			{
				$field = 'category';
			}
			if($foreign == 'category')
			{
				$field = 'id';
			}
			if($foreign == 'tag')
			{
				$field = 'entityid';					
			}
	
			$str .= '	//special field line controller.php line:95
						if(!isset($sessionuser)){ $sessionuser = $this->session->get(\'auth\'); }
						if($sessionuser[\'clearance\'] < 900)
						{
							$q = $this->orquery(\''.$field.'\',$this->check(\''.$foreign.'\'));
							$resultset = '.ucfirst($foreign).'::find(array(\'conditions\' => $q));
						}
						else
						{
							$resultset = '.ucfirst($foreign).'::find();
						}
						$this->view->setVar("'.$foreign.'s",$resultset);	
					';				
			$cc++;		
		}		
		return $str;
	}
	
	private function geteditrelations()
	{
		$str = '';

		$fields = array();	
		$columns = $this->columns;
		

		foreach($columns as $column)
		{ array_push($fields,$column['Field']);	}	
	
	
		//files
		if(in_array('fileid',$fields))
		{
			$str .= '
			
					//files
					$files = $this->getfiles(\''.$this->entity.'\',$'.$this->entity.'->id);		
					$this->view->setVar("files", $files);	
			
					';	
		}		
		
		$lrelations	= array();
		$linkrelations = $this->linkrelations;

		foreach($linkrelations as $linkrelation)
		{
			$exlr = explode('_',$linkrelation);
			$lrelations[$linkrelation] = array($exlr[0],$exlr[1]); 	
				
			if($exlr[0] == $this->entity)
			{ $foreign = $exlr[1]; }else{ $foreign = $exlr[0]; }
			
			$str .= '
	
					//relation
					$'.$foreign.'s = '.ucfirst($foreign).'::find();
					$this->view->setVar("'.$foreign.'s", $'.$foreign.'s);

					//relations
					$'.$this->entity.$foreign.'s = '.ucfirst($this->entity).ucfirst($foreign).'::find(\''.$this->entity.'id = "\'.$'.$this->entity.'->id.\'"\');
					$r'.$foreign.'s = array();

					foreach($'.$this->entity.$foreign.'s as $'.$this->entity.$foreign.')
					{ array_push($r'.$foreign.'s,$'.$this->entity.$foreign.'->'.$foreign.'id);	}
					$this->view->setVar("'.$this->entity.$foreign.'s",$r'.$foreign.'s);

					
				';
				
				/*
					//relations
					$berichttags = BerichtTag::find('berichtid = "'.$bericht->id.'"');
					$rtags = array();
					foreach($berichttags as $berichttag)
					{ array_push($rtags,$berichttag->tagid); }
					$this->view->setVar("berichttags",$rtags);
				*/
		}

		//relation
		return $str;
	
	
		/*
		//files
		$files = array($event->file);		
		$this->view->setVar("files", $files);
	
		//relation
		$tags = Tag::find();
		$this->view->setVar("tags", $tags);
		
		//relations
		$eventags = EventTag::find('eventid = "'.$event->id.'"');
		$rtags = array();
		foreach($eventags as $eventtag)
		{ array_push($rtags,$eventtag->id);	}
		$this->view->setVar("eventags",$rtags);
		
		*/
	}
	
	private function widgetsnew()
	{
		$str = '';
	
		$functions = $this->functions;
	
		if(count($functions) > 0)
		{
			foreach($functions as $function)
			{
				$parts = explode('_',$function);
				$rparts = array_reverse($parts);
				
				//echo $rparts[0];
				
					$contents =	file_get_contents(BASEURL.'backend/interfaces/'.$rparts[0].'.php');
				//	echo $contents;
					$str .= trim(trim($contents,'<?'),'');
			}
		}
		return $str;	
	}
		
	private function widgetsedit()
	{
		$str = '';
		$functions = $this->functions;
		if(count($functions) > 0)
		{
			foreach($functions as $function)
			{
				$parts = explode('_',$function);
				$rparts = array_reverse($parts);
				$contents =	file_get_contents(BASEURL.'backend/interfaces/'.$rparts[0].'.php');

				$str .= trim(trim($contents,'<?'),'');
			}
		}
		return $str;	
	}	
	
	private function widgetsadd()
	{
		$str = '';
		$functions = $this->functions;
		if(count($functions) > 0)
		{
			foreach($functions as $function)
			{
				$parts = explode('_',$function);
				$rparts = array_reverse($parts);
				$contents =	file_get_contents(BASEURL.'backend/interfaces/'.$rparts[0].'add.php');
				$str .= trim(trim($contents,'<?'),'');
			}
		}
		return $str;
	}
	
	private function widgetsview()
	{
		$str = '';
		$functions = $this->functions;
	
		if(count($functions) > 0)
		{
			foreach($functions as $function)
			{
				$parts = explode('_',$function);		
				$rparts = array_reverse($parts);
		
				$contents =	file_get_contents(BASEURL.'backend/interfaces/'.$rparts[0].'.php');
				//echo BASEURL.'backend/interfaces/'.$rparts[0].'.php';
				
				$a = array('#entity#','#id#','#view#','#widget#');
				$b = $parts;				
		
				$contents = str_replace($a,$b,$contents);
				$str .= trim(trim($contents,'<?'),'');
			}
		}
		return $str;	
	}	
	
	public function totext()
	{
		$contents =	file_get_contents(BASEURL.'backend/templates/backendcontroller.rsi');
		$a = array('#entity#','#Entity#','#addfields#','#filters#','#checks#','#searchcolumns#','#columns#','#tabs#','#specialfields#','#firstcolumn#','#relations#','#linkrelations#','#editrelations#');
		$b = array($this->entity,
					ucfirst($this->entity),
					$this->addcontroller(),
					$this->getfilters(),
					$this->getchecks(),
					$this->getsearchcolumns(),
					$this->getcolumns(),
					$this->gettabs(),
					$this->getspecialfields(),
					$this->firstcolumn,
					$this->getrelations(),
					$this->getlinkrelations(),
					$this->geteditrelations());
		return str_replace($a,$b,$contents);
	}
	
	

	public function tofile()
	{

		if(file_exists('../app/controllers/'.ucfirst($this->entity).'Controller.php'))
		{
			$usercontents =	file_get_contents('../app/controllers/'.ucfirst($this->entity).'Controller.php');
			$left = explode('#startuserspecific#',$usercontents);	

			if(isset($left[1])){ $right = explode('#enduserspecific#',$left[1]); }
	
			$usercontent  = '#startuserspecific#';		
			if(isset($right[0])){ $usercontent .= $right[0]; }
			$usercontent .= '#enduserspecific#';		
		}
		else
		{
			$usercontent  = '#startuserspecific#

							 #enduserspecific#';	
		}

		$contents =	file_get_contents(BASEURL.'backend/templates/backendcontroller.rsi');
		$a = array('#entity#','#Entity#','#addfields#','#filters#','#checks#','#searchcolumns#','#columns#','#tabs#','#specialfields#','#firstcolumn#','#relations#','#linkrelations#','#editrelations#','#widgetsnew#','#widgetsedit#','#widgetsadd#','#widgetsview#','#userspecific#');
		$b = array($this->entity,
					ucfirst($this->entity),
					$this->addcontroller(),
					$this->getfilters(),
					$this->getchecks(),
					$this->getsearchcolumns(),
					$this->getcolumns(),
					$this->gettabs(),
					$this->getspecialfields(),
					$this->firstcolumn,
					$this->getrelations(),
					$this->getlinkrelations(),
					$this->geteditrelations(),
					$this->widgetsnew(),
					$this->widgetsedit(),
					$this->widgetsadd(),
					$this->widgetsview(),					
					$usercontent);
		$model = str_replace($a,$b,$contents);
		$file = '../app/controllers/'.ucfirst($this->entity).'Controller.php';
		if(strlen($model) > 10){ file_put_contents($file,$model); 
		
		//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
		
		//linux goes haywire because the file system is a little different
		if(!is_writable($file)){ } chmod($file,0777); 
		
		}else{ 	return false; }
		
			return true;
	}  

	public function validation()
	{
		$this->validate(new Uniqueness(array(
		  'field' => 'title'
		)));
		return $this->getMessages();
	}

    public function columnMap()
    {
        return array('id' => 'id','title' => 'title','controllername' => 'controllername');
    }
}
	
	
