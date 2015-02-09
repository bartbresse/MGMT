<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;
	
class EntityController extends ControllerBase
{
    public function initialize()
    {
        Phalcon\Tag::setTitle('Settings');
        parent::initialize();
        $this->setModule('settings');
        $this->status = array('status' => 'false','messages' => array(),'column' => array());
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        //$this->session = $this->session->get('auth');
        $this->entity = 'MgmtEntity2';
        $this->lang = new MgmtLang();
	}

	public function indexAction()
	{
		//'systementity = 0'
	
            $this->setaction('MgmtEntity2');
            $this->setcolumns('MgmtEntity2');
            $entities = $this->search2(array('args' => '','columns' => array_keys(MgmtEntity2::columnMap()),'entity' => 'MgmtEntity2','order' => 'entityorder ASC')); 	
            $this->view->setVar('entities',$entities);
	}
        
        public function deletetestAction()
        {
            $entity = new MgmtEntity\MgmtEntity('viewtest1');
            
            echo $entity->name;
            
            $model6 = new MgmtEntity\MgmtViewFolder($entity);
            $model6->delete();die();
        }
	
	public function sortAction()
	{
            $this->view->disable();
            if($this->request->isPost()) 
            {
                $post = $this->post;

                $this->setaction('MgmtEntity2');
                $this->setcolumns('MgmtEntity2');

                $args = $this->getargs($post);
                $args['entity'] = 'MgmtEntity2';
                $args['columns'] = array_keys(MgmtEntity2::columnMap());	

                $entities = $this->search2($args);
                if(count($entities) > 0)
                {
                    $this->view->setVar("entities", $entities); 
                }
                
                $this->view->setvar("post", $post);
                $this->view->partial("entity/clean"); 	
            }
	}
	
	private function updateRegistry($e)
	{
		$entity = MgmtEntity2::findFirst('id = "'.$e->id.'"');
		if(!$entity)
		{
			$entity = new MgmtEntity2();
		}
		
		$entity->id = $e->id;
		$entity->name = $e->name;
		$entity->slug =  strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $entity->name));
		$entity->alias = $e->alias;
		$entity->clearance = $e->clearance;
		$entity->visible = 1;
		$entity->viewid = $this->uuid();
		//TODO: merge columns
		$entity->entity = $e->name;
		$entity->columns = serialize($e->columns);
		//TODO: figure out what it does
		$entity->single = 1;
		$entity->order = serialize($e->order);
		$entity->records = 0; 
//		$entity->show = $e->show;
		$entity->orderentity = 10;
		$entity->entityseperator = 0;
		$entity->entityorder = 0;
		$entity->newtext = $e->newtext;
		$entity->class = $e->class; 
		$entity->comment = $e->comment; 
		$entity->module = $e->module; 
		
		if($entity->save())
		{
                    $status['status'] = 'ok';
		}
		else
		{
                    foreach($entity->getMessages() as $message)
                    {
                        echo 'updateRegistry'.$message.'

                        ';
                    }
		}		
	}
	
	private function updateColumnRegistry()
	{
		
	
	}
	
	public function addAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->post;
			//TODO: create atleast one NOT NULL COLUMN
			
			$validation = new Phalcon\Validation();
			$validation->setFilters("name", array("string", "striptags"));	
			$validation->add('name', new PresenceOf(array(
				'field' => 'name',
				'message' => 'De entiteit naam is verplicht'
			)));
			
			$validation->add('name', new StringLength(array(
				'name' => 'De entiteit naam moet langer zijn dan 2 characters',
				'min' => 2
			)));
			
                        
                        
                        
			$messages = $validation->validate($post);
			
                        
                        if(count($messages))
			{
				for($i=0;$i<count($messages);$i++){
				$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
				array_push($status['messages'],$message); }
			}
                        
                        /**
                         * entity needs a unique entity name
                         */
                        $entity = MgmtEntity2::findFirst('name = "'.$post['name'].'"');
                        if($entity && !strlen($post['entityid']) > 0)
                        {
                            $message['name'] = 'Deze entiteit bestaat al'; 
                            array_push($status['messages'],$message);
                        }
                        
                        /**
                         * entity needs an unique entity alias
                         */
                        $entity = MgmtEntity2::findFirst('alias = "'.$post['alias'].'"');
                        if($entity && !strlen($post['entityid']) > 0)
                        {
                            $message['alias'] = 'Dit alias is al een keer gebruikt'; 
                            array_push($status['messages'],$message);
                        }
                        
                        
			if(count($status['messages']) == 0)
			{

				$entity = new MgmtEntity\MgmtEntity($post['name']);
				
				if(isset($post['entityid']) && strlen($post['entityid']) > 10)
				{
					$entityid = $post['entityid'];
					$entity->update = 1;
					$entity->id = $post['entityid'];
				}
				else
				{
					$entityid = $this->uuid();
					$entity->update = 0;
				}
				
				$entity->id = $entityid;
				$entity->alias = $post['alias'];
				$entity->clearance = $post['clearance'];
				$entity->newtext = $post['newtext'];
				$entity->module = $post['module'];
				
				if($post['module'] == 'entity')
				{
					$post['module'] = 'public';
				}
				
				$entity->formcolumns = MgmtColumn::find();
			
				// TODO: generate unique class			
				//	if(isset($post['class'])){ $entity->class = $post['class']; }
				if(isset($post['comment'])){ $entity->comment = $post['comment']; }
				if(isset($post['form-num']) && $post['form-num'] > 0)
				{
                                    //initiate relations
                                    if(isset($post['relationentity0']))
                                    {
                                        for($i=0;$i<20;$i++)
                                        {
                                            if(isset($post['relationentity'.$i]))
                                            {
                                                $foreigntable = MgmtEntity2::findFirst('name = "'.$post['relationentity'.$i].'"');

                                                $entityrelation = new MgmtRelation();
                                                $entityrelation->id = $this->uuid();
                                                $entityrelation->fromid = $entityid;
                                                $entityrelation->toid = $foreigntable->id;
                                                $entityrelation->fromname = $post['name'];
                                                $entityrelation->toname = $foreigntable->name;
                                                $entityrelation->relationtype = $post['relationtype'.$i];
                                                $entityrelation->null = 0;

                                                $entity->addRelation($entityrelation);
                                                $entityrelation->save();

                                                //IF HASONE
                                                if($post['relationtype'.$i] == 'HasOne')
                                                {
                                                    $entityline = new MgmtEntity\MgmtEntityLine();
                                                    $entityline->name = $foreigntable->name.'id';
                                                    $entityline->type = 'VARCHAR ( 36 )';
                                                    $entityline->default = 0;
                                                    $entityline->null = 0;
                                                    $entityline->comments = 'connection to '.strtolower($post['relationentity'.$i]).' table';
                                                    $entityline->show = 0;
                                                    $entityline->unique = 0;
                                                    $entityline->phalcontype = 'Id';

                                                    $entity->addLine($entityline);
                                                }

                                                //HAS MANY
                                                if(isset($post['relationtype'.$i]) && $post['relationtype'.$i] == 'HasMany')
                                                {
                                                    //TODO:entityid or connecting table
                                                    $relationentity = new MgmtEntity\MgmtEntity($post['name'].'_'.$foreigntable->name);		
                                                    $relationentity->id = $entityid;
                                                    $relationentity->alias = $post['alias'];
                                                    $relationentity->clearance = 900;

                                                    $entityline = new MgmtEntity\MgmtEntityLine();
                                                    $entityline->name = 'id';
                                                    $entityline->type = 'VARCHAR (36)';
                                                    $entityline->null = 0;
                                                    $entityline->unique = 1;
                                                    $relationentity->addLine($entityline);

                                                    $entityline = new MgmtEntity\MgmtEntityLine();
                                                    $entityline->name = $post['name'].'id';
                                                    $entityline->type = 'VARCHAR (36)';
                                                    $entityline->null = 0;
                                                    $entityline->unique = 1;
                                                    $relationentity->addLine($entityline);

                                                    $entityline = new MgmtEntity\MgmtEntityLine();
                                                    $entityline->name = $foreigntable->name.'id';
                                                    $entityline->type = 'VARCHAR (36)';
                                                    $entityline->null = 0;
                                                    $entityline->unique = 1;
                                                    $relationentity->addLine($entityline);

                                                    $adapter = new MySQLTableAdapter();
                                                    $sql = $adapter->createTable($relationentity);
                                                    $this->db->execute($sql);
                                                }
                                                //TODO: other relation types
                                            }
                                        }
                                    }
				
                                    $entitycolumnnames = array();	
                                    for($i=0;$i<($post['form-num']+1);$i++)
                                    {
                                        if(isset($post['name'.$i]))
                                        {
                                            $entitycolumn = new MgmtEntitycolumn();
                                            $entitycolumn->id = $this->uuid();

                                            array_push($entitycolumnnames,$post['name'.$i]);

                                            $entitycol = MgmtEntitycolumn::findFirst('mgmtentityid = "'.$entity->id.'" AND name = "'.$post['name'.$i].'"');
                                            if($entity->update == 1 && $entitycol)
                                            {
                                                    $entitycolumn = $entitycol;
                                            }

                                            $entitycolumn->mgmtentityid = $entity->id;							

                                            $entityline = new MgmtEntity\MgmtEntityLine();
                                            $properties = $entityline->getProperties();

                                            foreach($properties as $property)
                                            {
                                                    $entityline->$property = $post[$property.$i];
                                                    $entitycolumn->$property = $post[$property.$i];
                                            }

                                            if($post['length'.$i] > 0)
                                            {
                                                    $entityline->type = strtoupper($post['type'.$i]).'( '.$post['length'.$i].' )';
                                            }
                                            else
                                            {
                                                    $entityline->type = strtoupper($post['type'.$i]);
                                            }

                                            $mgmtcolumn = MgmtColumn::findFirst('id = "'.$entityline->mgmttype.'"');
                                            if($mgmtcolumn)
                                            {
                                                    $entityline->phalcontype = $mgmtcolumn->phalcontype;
                                                    $entitycolumn->phalcontype = $mgmtcolumn->phalcontype;
                                            }

                                            $entitycolumn->mgmttype = $post['mgmttype'.$i];	

                                            if($entitycolumn->save())
                                            {
                                                    $status['status'] = 'ok';
                                            }
                                            else
                                            {
                                                    foreach($entitycolumn->getMessages() as $message)
                                                    {
                                                            echo 'entitycolumn:'.$message.'

                                                            ';
                                                    }
                                            }			
                                            $entity->addLine($entityline);
                                        }
                                    }

                                    $entitycols = MgmtEntitycolumn::find('mgmtentityid = "'.$entity->id.'"');
                                    foreach($entitycols as $entitycol)
                                    {
                                        if(!in_array($entitycol->name,$entitycolumnnames))
                                        {
                                            $entitycol->delete();
                                        }
                                    }

                                    //MANAGE WIDGETS
                                    for($i=0;$i<20;$i++)
                                    {
                                        if(isset($post['relationwidget'.$i]))
                                        {
                                            $entity->addWidget();
                                        }
                                    }
				}
		
				if($entity->update == 1)
				{
					$adapter = new MySQLTableAdapter();
					$original = $adapter->getTable($entity);
					$sql = $adapter->updateTable($original,$entity);
					foreach($sql as $q)
					{
						$this->db->execute($q);
					}	
				}
				else
				{
					$adapter = new MySQLTableAdapter();
					$sql = $adapter->createTable($entity);
					$this->db->execute($sql);
				}				
				//UPDATE ENTITY REGISTRY
				$this->updateRegistry($entity);
				//GENERATE CRUD
				$entity->generate();	
			}
		}
		echo json_encode($status);
	}
	
	private $mgmtcolumns = array();
	private function getMGMTcolumns()
	{
		$html = '';
		$columns = MgmtColumn::find();
		foreach($columns as $column)
		{
			$html .= '<option value="'.$column->id.'">'.$column->titel.'</option>';
			$this->mgmtcolumns[$column->id] = $column->titel;
		}
		return $html;
	}
	
	private function getOptions()
	{
		return array('VARCHAR','INT','BOOLEAN','DATETIME','DATE','TEXT');
	}
	
	private function getOptions2()
	{
		$html = '';
		$options = array('VARCHAR','INT','BOOLEAN','DATETIME','DATE','TEXT');
		foreach($options as $option)
		{
			$html .= '<option value="'.$option.'">'.$option.'</option>';
		}
		return $html;
	}
	
	public function getjsonentityAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$status['mgmtcolumns'] = $this->getMGMTcolumns();
			$status['columns'] = $this->getOptions2();
		}	
		echo json_encode($status);
	}
	
	public function setlineAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$post = $this->post;	
			
			$column = MgmtColumn::findFirst('id = "'.$post['mgmttype'].'"');
			$cm = $column->columnMap();
			
			foreach($cm as $c)
			{ $status['column'][$c] = $column->$c; }	
			
			$e = explode('mgmttype',$post['num']);
			$status['num'] = $e[1]; 
		}
		echo json_encode($status);
	}
	
	public function newAction()
	{
		$this->view->setVar("mgmtoptions",$this->getMGMTcolumns());
		$this->view->setVar("options",$this->getOptions());
		
		$form = new Form();
		$session = $this->session->get('auth');
		$bericht = Bericht::findFirst();
		if(!isset($bericht->id))
		{
			$bericht = new Bericht();
			$bericht->userid = $session['id'];
		}
		
		$form = new Form();
		$entity = new MgmtEntity2();
		$cm = $entity->columnMap();
		
		$relations = MgmtRelation::find('id = "34783264732647236"');
		$this->view->setVar('relations',$relations);
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		$form->add(new Select("module", MgmtModule::find(),array('using' => array('titel', 'titel'),'id' => 'module','class' => 'form-control')));	
		$form->add(new Select('entities',MgmtEntity2::find(),array('using' => array('entity','entity'),'value' => '','id' => 'entities','class' => 'form-control event-multiple-select')));
		$form->add(new Select('entities2',MgmtEntity2::find(),array('using' => array('entity','entity'),'value' => '','id' => 'entities2','class' => 'form-control event-multiple-select')));
	
		$this->view->setVar('form',$form);
	}
	
	private function getTable($e)
	{
		$entity = new MgmtEntity\MgmtEntity($e->name);
		$entity->alias = $e->alias;
		$entity->clearance = $e->clearance;
		$entity->newtext = $e->newtext;
		$entity->class = $e->class; 
		$entity->comment = $e->comment; 
	
		$columns = MgmtEntitycolumn::find('mgmtentityid = "'.$e->id.'"');
		foreach($columns as $column)
		{
			$mgmtcolumn =  MgmtColumn::findFirst('id = "'.$column->mgmttype.'"');
			
		//	echo $column;
			
		}
	
		$columnx = $this->db->fetchAll("SHOW FULL COLUMNS FROM `".strtolower($e->name)."`", Phalcon\Db::FETCH_ASSOC);
		foreach($columnx as $column)
		{
			
			$mgmtcolumn = MgmtColumn::findFirst('titel = "'.$column['Field'].'"');
			if($mgmtcolumn)
			{
				$entitycolumn = MgmtEntitycolumn::findFirst('mgmtentityid = "'.$e->id.'" AND mgmttype = "'.$mgmtcolumn->id.'"');
				
				$entityline->show = $entitycolumn->show;
				$entityline->mgmttype = $mgmtcolumn->id;
			}
			
		
			$entityline = new MgmtEntity\MgmtEntityLine();
			$entityline->name = $column['Field'];
			
			$type = explode('(',$column['Type']);
			$entityline->type = $type[0];
			
			if(isset($type[1]))
			{
				$entityline->length = rtrim($type[1],')');
			}
			if($column['Null'] == 'YES')
			{
				$entityline->null = 1;
			}
			else
			{
				$entityline->null = 0;
			}
			if($column['Key'] == 'UNI')
			{
				$entityline->unique = 1;
			}
			else
			{
				$entityline->unique = 0;
			}
			$entityline->comment = $column['Comment'];
			$entityline->default = $column['Default'];
			$entity->addLine($entityline);
		}
		return $entity;
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');
		$entity = MgmtEntity2::findFirst('id = "'.$id.'"');
	
		$cm = $entity->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $entity->$column,'id' => $column,'class' => 'form-control'))); }	
		
		$form->add(new Password("password", array("id" => "password","class" => "form-control")));
		$form->add(new Password("password2", array("id" => "password2","class" => "form-control")));

		$this->view->setVar("entity", $entity);		
		$this->view->setVar("entityid", $entityid);
		
		
		$entitycolumns = MgmtEntitycolumn::find('mgmtentityid = "'.$entity->id.'"');
		$this->view->setVar("lines", $entitycolumns);
		
		$relations = MgmtRelation::find('fromid = "'.$id.'"');
		$this->view->setVar('relations',$relations);
		
		$this->view->pick("entity/new");	
	
		$this->getMGMTcolumns();
		$this->view->setVar("mgmtoptions",$this->mgmtcolumns);
		
		$this->view->setVar("options",$this->getOptions());
		
		$form->add(new Select("module", MgmtModule::find(),array('using' => array('titel', 'titel'),'value' => $entity->module,'id' => 'module','class' => 'form-control')));
		$form->add(new Select('entities',MgmtEntity2::find(),array('using' => array('entity','entity'),'value' => '','id' => 'entities','class' => 'form-control event-multiple-select')));
		$form->add(new Select('entities2',MgmtEntity2::find(),array('using' => array('entity','entity'),'value' => '','id' => 'entities2','class' => 'form-control event-multiple-select')));
				
		$this->view->setVar('form',$form);
	}
	
	public function deleteAction()
	{
		$this->view->disable();
		$status = $this->status;
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$user = MgmtEntity2::findFirst('id = "'.$post['id'].'"');			
			
			$entity = new MgmtEntity\MgmtEntity($user->name);
			$entity->delete();
			
			//TODO: delete table
                        try 
                        {
                            $this->db->execute('DROP TABLE `'.$user->name.'`');
                        } 
                        catch(\Exception $e) 
                        {
                            array_push($status['messages'],'TABLE DOESNT EXIST ANYMORE');
                        }
                        
                        
			$columns = MgmtEntitycolumn::find('mgmtentityid = "'.$user->id.'"');
			foreach($columns as $column)
			{
				$column->delete();
			}
			
			if($user->delete())
			{
				$status['status'] = 'ok';
			}
			
		}
		echo json_encode($status);
	}
	
	public function updateentityorderAction()
	{
		$this->view->disable();
		$status = $this->status;
		$status['status'] = 'true';
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			for($i=0;$i<count($post['order']);$i++)
			{
				$entity = MgmtEntity2::findFirst('id = "'.$post['order'][$i].'"');
				$entity->entityorder = $i;
				if(!$entity->save())
				{
					$status['status'] = 'false';
				}
			}
		}
		echo json_encode($status);
	}
	
	public function newseperatorAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$user = MgmtEntity2::findFirst();
		if(!isset($user->id))
		{
			$user = new MgmtEntity2();
			$user->userid = $this->uuid(); 
		}
		
		$cm = $user->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		$this->view->setVar("mgmtoptions",$this->getMGMTcolumns());
		$this->view->setVar("options",$this->getOptions());
	
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
	}
	
	/*
	private function getEntity($entity,$post)
	{
		$new = false;
		//$userx = $entity::findFirst('id = "'.$post['id'].'"'); 
		$user = new $entity();
		 $user->id = $this->uuid(); 
		$new = true;
		
		return $user;
	}*/
	
	public function addseperatorAction()
	{
		$this->view->disable();
		$status = $this->status;
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$validation = new Phalcon\Validation();
			$validation = $this->striptagsfilter($validation,array("titel"));
			
			$validation->add('titel', new PresenceOf(array(
				'field' => 'titel',
				'message' => 'Het veld titel is verplicht.'
			)));

			$validation->add('titel', new StringLength(array(
				'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$user = $this->getEntity('MgmtEntity2',$post);
				//save standard columns 
				foreach ($user as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && strlen($post[$key]) > 0)
					{ 
						$user->$key = $post[$key]; 
					}				
				}
				$user->entity = $post['titel'];
				$user->columns = serialize(array(''));
				$user->single = 1;
				$user->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));;
				$user->alias = $post['titel'];
				
				$user->entityseperator = 1;
				$user->entityorder = 9999;
			
				if($user->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($user->getMessages() as $message)
					{
							echo 'MgmtEntity2'.$message.'
							';
					}
				}		
			}
		}
		echo json_encode($status);
	}
	
	public function generatefileAction()
	{
		$model = 'test.volt';
		$file = '../app/views/plane/test.volt';
		if(is_file($file)){ chmod($file,0777); } 
		
		file_put_contents($file,$model);
		chmod($file,0777); 
		 
	
	}
	
	public function generatemodelAction()
	{
		$form = new Form();
		$form->add(new Text('table', array('id' => 'table','class' => 'form-control')));
		$this->view->setVar("form", $form);
	}
	
	public function addmodelAction()
	{
		$this->view->disable();
		$status = $this->status;
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			
			$entity = new MgmtEntity2();
			$entity->titel = $post['table'];
			$entity = $this->getTable($entity);
			$model = new MgmtEntity\MgmtModel($entity);
			$model->toFile();	
		}
		echo json_encode($status);
	}
	
}
?>
