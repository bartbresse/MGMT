<?php

class ProcessController extends ControllerBase
{
    public function initialize()
    {
		//  $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Welcome');
        parent::initialize();
    }

    public function indexAction()
    {
		
    }

	public function deleteentityAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$entity = Entity::findFirst('id = "'.$post['entityid'].'"');
			if($entity->delete())
			{
				$status['status'] = 'ok';
			}	
		}
		echo json_encode($status);
	}

	public function addcontrollerAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$id = $this->uuid();
	
			$post = $this->request->getPost();		
			$controller = new Controller();
			$controller->title = $post['title'];
			$controller->controllername = $post['controllername'];
			$controller->id = $id;
		
			$view = new Controllerview();
			$view->title = 'index';
			$view->struct = 0;	
			$view->controllerid = $id;
								
			if($view->save())
			{
				$status['status'] = 'halfok';
			}	
			else
			{
				foreach ($view->getMessages() as $message)
				{
					echo $message;
				}
			}			

			if($controller->save())
			{
				$status['status'] = 'ok';		
			}	
			else
			{
				foreach ($controller->getMessages() as $message)
				{
					echo $message;
				}
			}			
		}
		echo json_encode($status);
	}

	public function addviewAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			
			if(isset($_POST['viewid']))
			{
				$view = Controllerview::findFirst('id = "'.$post['viewid'].'"');
			}
			else
			{
				$view = new Controllerview();
			}			
	
			$view->title = $post['view'];
			$view->controllerid = $post['controllerid'];
		
			if(isset($_POST['struct']) && $_POST['struct'] == 1)
			{ $view->struct = 1; }
			else
			{ $view->struct = 0; } 
			
			if(isset($_POST['login']) && $_POST['login'] == 1)
			{ $view->login = 1; }
			else
			{ $view->login = 0; } 
			
			if($view->save())
			{
				$status['status'] = 'ok';
			}	
			else
			{
				foreach ($view->getMessages() as $message)
				{
					echo $message;
				}
			}			
		}
		echo json_encode($status);
	}

	public function addentityAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();

			if(isset($post['entityid']))
			{
				$entity = Entity::findFirst('id = "'.$post['entityid'].'"');	
			}
			else
			{
				$entity = new Entity();
				$entity->viewid = $post['id'];
			}
			
			$entity->title = $post['entity'];

			if(isset($post['single']) && $post['single'] == 1)
			{ $entity->single = 1; }
			else
			{ $entity->single = 0; }

			$entity->args = $post['args'];
	
			if($entity->save())
			{
				$status['status'] = 'ok';
			}	
			else
			{
				foreach ($entity->getMessages() as $message)
				{
					echo $message;
				}
			}			
		}
		echo json_encode($status);
	}

	/* this function generates the code for a entity controller but not the file */
	public function generatecontrollerAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$noincludes = array('file');
			$images = array();
			$functions = array();
			$widgets = array();
			$tables = array();
		
			$table = $post['table'];			
			$columns = $this->db->fetchAll("DESCRIBE ".$table, Phalcon\Db::FETCH_ASSOC);

			$postkeys = array_keys($post);
			foreach($postkeys as $var)
			{
				$v = explode('_',$var);
				if(isset($v[1]))
				{
					switch($v[0])
					{
						case 'file':
						array_push($images,$v[1]);
						break;
						case 'functions':
							if(is_array($post[$var]))
							{											
								foreach($post[$var] as $function)
								{	
									$entity = $v[1];
									$value = explode('_',$function);
									$widgetentity = $value[0];
									$widgetentityid = $value[1];
									$table = array($entity,array('widgetentity' => $widgetentity,'widgetentityid' => $widgetentityid));
									array_push($functions,$table);
								}
							}
						break;
						case 'widget':
							array_push($widgets,$v[1]);
						break;
					}
				}
				else
				{
					if($post[$var] == 1)
					{
						array_push($tables,$var);
					}
				}
			}

			//get all tables
			$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);

			//generate view relations for detail view
			$linkentityrelations = array();
			$entityrelations = array();
			foreach($tablesq as $tablex)
			{		
				$table3 = explode('_',$tablex['Tables_in_'.DATABASENAME]);
				if(!isset($table3[1]))
				{
					$columnx = $this->db->fetchAll("DESCRIBE `".$tablex['Tables_in_'.DATABASENAME]."`", Phalcon\Db::FETCH_ASSOC);
					foreach($columnx as $column)
					{
						if($table.'id' == $column['Field'])
						{
							if($tablex['Tables_in_'.DATABASENAME] != 'acl')
							{
								array_push($entityrelations,$tablex['Tables_in_'.DATABASENAME]);		
							}
						}
					}
				}
				else
				{
					if($table3[0] == $table || $table3[1] == $table)
					{
						array_push($linkentityrelations,$tablex['Tables_in_'.DATABASENAME]);
					}
				}	
			}

			$controller = new Controller();
			$controller->entity = $post['table'];
			$controller->columns = $columns;
			$controller->relations = $entityrelations;
			$controller->linkrelations = $linkentityrelations;
			$controller->tables = $tables;		
			$controller->images = in_array($table,$images);		
			echo $controller->totext();
		}
		echo json_encode($status);
	}
	
	public function language($lang)
	{
		$row = 1;
		$parselang = array();
		$langnum = 0;

		if (($handle = fopen(BASEURL.'backend/templates/nl.csv', "r")) !== FALSE) 
		{
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				//row 1 are language keys
				if($row == 1)
				{ 
					$languages = $data; 
					for($i=0;$i<count($languages);$i++)
					{
						
						if($languages[$i] == $lang)
						{
							$langnum = $i;		
						}
					}
				}			
				//get en - $lang tranlations
				$parselang[$data[0]] = $data[$langnum];	
				$row++;
			}
			fclose($handle);
		}

		return $parselang;
	}

	public function initAction()
	{
		//models that dont need creating
		$preservemodels = array('category','Category','file','File','EmailMessage','emailmessage','tag','mgmt_entity','mgmt_lang');
		$preserve = array('emailmessage','user','tag','mgmtlang','category');
	
		//standard language module is also basis for the front end module
		$language = $this->language('NL');

		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$noincludes = array('file');

			$images = array();
			$functions = array();//array for new/edit extra functions information
			$widgets = array();
			$tables = array();
			$ctables = array();
			
			$postkeys = array_keys($post);
			foreach($postkeys as $var)
			{
				$v = explode('_',$var);
				if(isset($v[1]))
				{
					switch($v[0])
					{
						case 'file':
							if($post[$var] == 1){ array_push($images,$v[1]); }
						break;
						case 'functions':
							if(is_array($post[$var]))
							{											
								foreach($post[$var] as $function)
								{	
									$entity = $v[1];
									$value = explode('_',$function);
									$widgetentity = $value[0];
									$widgetentityid = $value[1];
									$table = array($entity,array('widgetentity' => $widgetentity,'widgetentityid' => $widgetentityid));
									array_push($functions,$table);
								}
							}
						break;
						case 'widget':
							array_push($widgets,$v[1]);
						break;
					}
				}
				else
				{
					if($post[$var] == 1)
					{
						array_push($tables,$var);
					}
				}
			}

			//SETTINGS SLUG & ALIAS SAVE 		NEW/EDIT & VIEW WIDGETS ALSO
			
			//mgmtentities cant update for some reason
			
			
			$ent = MgmtEntity::find();
			foreach($ent as $e)
			{
				$e->delete();
			}
			
			foreach($tables as $table)
			{			
				if(isset($post['clearance_'.$table]) && isset($post['alias_'.$table]))
				{
					$mgmt_entity = MgmtEntity::findFirst('titel = "'.$table.'"');
					if(!$mgmt_entity)
					{	
						$mgmt_entity = new MgmtEntity();	
					}
					$mgmt_entity->id = $this->uuid();
					$mgmt_entity->titel = $table;
					$mgmt_entity->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $table));
					$mgmt_entity->clearance = $post['clearance_'.$table];
					$mgmt_entity->alias = $post['alias_'.$table];		
					
					$mgmt_entity->newedit = serialize($post['functions_'.$table.'_new']);
					$mgmt_entity->view = serialize($post['functions_'.$table.'_view']);
					
					if($post[$table] == 1)
					{ $val = 1;	}else{$val = 0;}
					
					$mgmt_entity->visible = $val;
		
					if($mgmt_entity->save())
					{ 
						
					}
					else
					{
						foreach ($mgmt_entity->getMessages() as $message)
						{
								echo $message;
						}
					}
				}
			}
		
			//get all tables
			$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
			$linktables = array();
			foreach ($tablesq as $tableq) 
			{
				$ext = explode('_',$tableq['Tables_in_'.DATABASENAME]);
				if(isset($ext[1]))
				{	array_push($linktables,$tableq['Tables_in_'.DATABASENAME]);	}
				
				array_push($ctables,$tableq['Tables_in_'.DATABASENAME]);
			}

			//tables with columns
			$tablesobject = array();
			foreach ($tablesq as $tableq) 
			{
				$tablesobject[$tableq['Tables_in_'.DATABASENAME]] = array();
				$columns = $this->db->fetchAll("DESCRIBE `".$tableq['Tables_in_'.DATABASENAME]."`", Phalcon\Db::FETCH_ASSOC);
				foreach($columns as $column)
				{
					array_push($tablesobject[$tableq['Tables_in_'.DATABASENAME]],$column['Field']);	
				}
			}

			$error =0;
			//create models for link tables	
			foreach($linktables as $linktable)
			{
				if(!in_array($linktable,$preservemodels))
				{
					$model = new Modell();
					$model->name = $linktable;
					$columns = $this->db->fetchAll("DESCRIBE ".$linktable, Phalcon\Db::FETCH_ASSOC);
					$model->columns = $columns;
					
					$model->tables = $tables;
					$model->tablesobject = $tablesobject;
					if(!$model->tofile()){ $error++; }
				}
			}
			
			//create models			
			foreach($tables as $table)
			{
				if(!in_array($table,$preserve))
				{
					$model = new Modell();
					$model->name = $table;
					$columns = $this->db->fetchAll("DESCRIBE ".$table, Phalcon\Db::FETCH_ASSOC);
					$model->columns = $columns;
					$model->tables = $ctables;
					$model->tablesobject = $tablesobject;
					if(!$model->tofile()){ $error++; }
				
					
					$relations = array();
					foreach($tables as $tablex)
					{
						$columnx = $this->db->fetchAll("DESCRIBE ".$tablex, Phalcon\Db::FETCH_ASSOC);
						foreach($columnx as $column)
						{
							if($column['Field'] == $tablex.'id')
							{
								array_push($relations,$column['Field']);	
							}
						}
					}
					
					//generate view relations for detail view
					$linkentityrelations = array();
					$entityrelations = array();
					foreach($tablesq as $tablex)
					{		
						$table3 = explode('_',$tablex['Tables_in_'.DATABASENAME]);
						if(!isset($table3[1]))
						{
							$columnx = $this->db->fetchAll("DESCRIBE `".$tablex['Tables_in_'.DATABASENAME]."`", Phalcon\Db::FETCH_ASSOC);
							foreach($columnx as $column)
							{
								if($table.'id' == $column['Field'])
								{
									if($tablex['Tables_in_'.DATABASENAME] != 'acl')
									{
										array_push($entityrelations,$tablex['Tables_in_'.DATABASENAME]);		
									}
								}
							}
						}
						else
						{
							if($table3[0] == $table || $table3[1] == $table)
							{
								array_push($linkentityrelations,$tablex['Tables_in_'.DATABASENAME]);
							}
						}	
					}
					
					
					$controller = new Controller();
					$controller->language = $language;				
					$controller->entity = $table;
					$controller->columns = $columns;
					$controller->relations = $entityrelations;
					$controller->linkrelations = $linkentityrelations;
					$controller->tables = $tables;		
					$controller->images = in_array($table,$images);		
					
				
					$view = new View();
					$view->language = $language;
					$view->entity = $table;
					$view->columns = $columns;
					$view->relations = $entityrelations;
					$view->linkrelations = $linkentityrelations;
					$view->baseuri = $this->url->getBaseUri();		
					$view->images = in_array($table,$images); 
					$view->tablesobject = $tablesobject;
					
					$functions = array();
					if(isset($post['functions_'.$table.'_new']) && is_array($post['functions_'.$table.'_new']) )
					{	
						foreach($post['functions_'.$table.'_new'] as $function)
						{
							array_push($functions,$function);
						}
					}
				
					if(isset($post['functions_'.$table.'_view']) && is_array($post['functions_'.$table.'_view']) )
					{	
						foreach($post['functions_'.$table.'_view'] as $function)
						{
							array_push($functions,$function);
						}
					}
					
					if(count($functions) > 0)
					{ 
						$view->functions = $functions; 
						$controller->functions = $functions;
					}

					if(!$view->tofile()){ $error++; }	
					
					if(!$controller->tofile()){ $error++; }	
				}
			}	

			$menu = new Menu();
			$menu->tables = MgmtEntity::find(array('order' => 'titel ASC'));
			if(!$menu->tofile()){ $error++; }
			
			if(!isset($error) || $error == 0)
			{
				$status['status'] = 'ok';			
			}
			else
			{
				$status['messages'] = 'Something went wrong!';
			}
			
			
		}
		echo json_encode($status);
	}


	//creates an array of possible entity properties 
	//used in the front views 
	// single 1 = Entity::findFirst 
	public $columns = array();
	public function getcolumnproperties($entity,$prepend,$baseentity,$single)
	{
		$columnx = $this->db->fetchAll("DESCRIBE `".$entity."`", Phalcon\Db::FETCH_ASSOC);

		
		foreach($columnx as $column)
		{
			$id = explode('id',$column['Field']);
			if(!isset($id[1]))
			{ 
				$str = $prepend.$column['Field'].';
						';	
				array_push($this->columns,array('str' => $str,'entity' => $baseentity,'single' => $single));		
			}
			else
			{ 
				$n = $id[0];
				if(strlen($id[0]) > 0 && $id[0] != 'parent' && $id[0] != 'view')
				{
				
					
					$this->getcolumnproperties($n,$prepend.$n.'->',$baseentity,$single);		 			
				}				
			}
		}
		return $this->columns;			
	}
	
	public function initfrontendAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$controllers = Controller::find();	
			foreach($controllers as $controller)
			{
				//create folder
				$foldername = strtolower(str_replace('_','',$controller->controllername));		
				if (!file_exists('../../app/views/'.$foldername))
				{
					mkdir('../../app/views/'.$foldername, 0777, true);
				}
				chmod('../../app/views/'.$foldername,0777); 

				foreach($controller->Controllerview as $view) 
				{
					$entities = array();
					//views have entities to show eg products users 
					foreach($view->entity as $entity)
					{
						//every entity(table) has properties(columns)
						$this->getcolumnproperties($entity->title,'$'.$entity->title.'->',$entity->title,$entity->single);
					}					
		
					$page = new Page();	
					$page->entities = $this->columns;	
					$page->page = $controller;
					$page->view = $view;
					$page->folder = $foldername;
					$page->tofile();
				}
			
				
			
				//generate entity frontend controllers
				$fc = new Frontendcontroller();
				$fc->controller = $controller;		
				$fc->controllerviews = $controller->Controllerview;
				$fc->tofile();
				
			}	

			//generate menu
			$menu = new Frontendmenu();
			$menu->controllers = $controllers;
			$menu->tofile();
			//TODO create decent pages before sitecontroller
			

			//generate sitemap controller
			//REPLACED by a dynamic script using the database tables
			
			/*	$controllers = Controller::find();	
				$sitemap = new Sitemapcontroller();
				$sitemap->controllers = $controllers; 
				$sitemap->tofile();	*/	
		}		
		echo json_encode($status);
	}
}
