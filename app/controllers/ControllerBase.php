<?php

class ControllerBase extends Phalcon\Mvc\Controller
{
    protected $user;
    protected $new;
    protected $entity;
    protected $status = array('status' => 'false','messages' => array());
    protected $post;
    public $lang;
    protected $get;
//  protected $session;
	
    protected function initialize()
    {
        Phalcon\Tag::prependTitle('MGMT | ');

        $security = new Security();
        if($this->session->get('auth'))
        { 
            $this->user = $this->session->get('auth'); 
        }

        $this->view->setVar("tables", MgmtEntity2::find(array('order' => 'orderentity ASC')));
        //$this->view->setVar("subtables", MgmtEntity::find(array('conditions' => 'orderentity > 100 ','order' => 'titel ASC'))); 

        $this->get = $this->request->getQuery();
        
        $exp = explode('/',trim($this->get['_url'],'/'));  
        $this->view->setVar("mgmtentity",MgmtEntity2::findFirst('slug = "'.$exp[0].'"'));
       
        /**
         * to initiate modules in the header 
         */
        $this->view->setVar('modules',MgmtModule::find());

        //MULTILANG MODULE
        $lang = new MgmtLang();
        $this->lang = $lang;
        $this->view->setVar("lang", $lang);
        $this->view->setVar("name", "Mike");
        $this->view->setVar("t", $this->_getTranslation());

        //USER SESSION
        $auth = $this->session->get('auth');
        $this->view->setVar("auth", $auth);

        $user = User::findFirst('id = "'.$auth['id'].'"');
        
        $this->view->setVar("user", $user);
        $this->post = $this->request->getPost(); 
    }
	
    /**
     * standard phalcon language initialization files
     * 
     * @return \Phalcon\Translate\Adapter\NativeArray
     */
    protected function _getTranslation()
    {
        //Ask browser what the best language is
        $language = $this->request->getBestLanguage();
        //Check if we have a translation file for that lang
        
        $language = 'nl-NL';
        
        if(file_exists("../app/messages/".$language.".php")) 
        {
            require "../app/messages/".$language.".php";
        } 
        else 
        {
            require "../app/messages/en.php";
        }

        //Return a translation object
        return new \Phalcon\Translate\Adapter\NativeArray(array(
            "content" => $messages
        ));
    }

    protected function setModule($module)
    {
        $this->view->setVar("tables", MgmtEntity2::find(array('order' => 'orderentity ASC','conditions' => 'module = "'.ucfirst($module).'"')));
    }

    /*
            authentication tests
    */

    //tableview functionality
    private $actions = array('create','edit','deactiveren','delete','sort','export');

    //arguments 
    private $arguments;

    public function auth($object)
    {
            $session = $this->session->get('auth');
            $acl = Acl::findFirst('userid = "'.$session['id'].'" ');			
    }

    public function actionauth($entity)
    {
            $session = $this->user;
            if($session['clearance'] < 900 && $entity!='User')
            {
                    $actions = array();
                    $acl = Acl::find('(clearance > "'.$session['clearance'].'" OR userid = "'.$session['id'].'" ) AND request = 1 AND end = 1 AND entity = "'.$entity.'"');
                    foreach($acl as $action)
                    {
                            array_push($actions,$action->actie);		
                            if(strlen($action->args) > 0)
                            { $arguments = $action->args.' ';	}
                    }	

                    //arguments
                    if(isset($arguments)){ $this->arguments = $arguments; }
                    //actions  

                    return $actions;
            }
            elseif($session['clearance'] < 900 && $entity=='User') {
                    $actions = array('create','deactiveren','sort','export');
                    if($session['clearance'] < 100) $actions = array();
                    return $actions;
            }
            else
            {
                    return $this->actions;
            }		
    }

    public function removeauth()
    {
            $session = $this->session->get('auth');
    }

	/*
		end authentication
	*/
    public function beforeExecuteRoute($dispatcher)
    {	
		// TODO verify that this is decent security
		// This is executed before every found action
		$usersession = $this->session->get("auth");
		
		$acl = Acl::find('userid = "'.$usersession['id'].'" AND clearance > 200 ');
		
		if(count($usersession) > 0 && count($acl) > 0 &&isset($usersession['clearance']) || $dispatcher->getControllerName() == 'cron'  || $dispatcher->getControllerName() == 'session')
		{
			if(!defined('USER')){ define('USER','loggedin'); } 
		}
		else if($dispatcher->getActionName() != 'login' && $dispatcher->getActionName() != 'start' && $dispatcher->getActionName() != 'logout')
		{
			$this->dispatcher->forward(array(
				'controller' => 'session',
				'action' => 'login'
			));
		}	
    }


	public function search2($args)
    {
		//http://docs.phalconphp.com/en/latest/reference/models.html#finding-records
		//search string
		if(!isset($args['search']))
		{
			$q = $this->request->getQuery('q');
		}
		else if($args['search'] != false)
		{
			$q = $args['search'];
		}
		else
		{
			$q = '';
		}
		
		$query = array();
		$query['conditions'] = '';
		
		//search string
		if(strlen($q) > 1 )
		{
			$columns = $args['columns'];
			$cc = 0;$sq=' ( ';
			foreach($columns as $column)
			{
				$sq .= $column.' Like "%'.$q.'%" ';
				if(count($columns)-1> $cc){ $sq .= ' OR '; }
				$cc++;
			}	
			$sq .= ' ) ';
			$query['conditions'] = $sq;
		}
		
		//basic arguments
		if(isset($args['args']) && strlen($args['args']) > 1)
		{
			$sq = $query['conditions']; 
			if(strlen($sq) > 1){ $sq .= ' AND '; }
			$sq .= $args['args'];
			$query['conditions'] = $sq;
		}

		//authentication arguments
		if(strlen($this->arguments) > 1)
		{
			$sq = $query['conditions']; 
			if(strlen($sq) > 1){ $sq .= ' AND '; }	
			$sq .= $this->arguments;
			$query['conditions'] = $sq;
		}		
		

		//order by 
		if(isset($args['order']) && strlen($args['order']) > 1)
		{
			//â€œorderâ€? => â€œname DESC, statusâ€?
			$query['order'] = $args['order'];
		}

		$entity = ucfirst($args['entity']);
	
		$items = $entity::find($query);
		
		if(isset($args['rows'])){ $rows = $args['rows']; }else{ $rows = 20; }
	
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => $items,
				"limit"=> $rows,
				"page" => $this->request->getQuery("p", "int", 1)
			)
		);

		// Get the paginated results
		$items = $paginator->getPaginate();			
		return $items; 		
    }

	
	public function updatecolumnorderAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$columns = array();
			$column = Column::findFirst('entity = "'.trim($post['entity'],'/').'"');
			if(!isset($column->id))
			{
				$column = new Column(); 
			}
			
			$column->entity = trim($post['entity'],'/');
			$column->order = $post['order'];
			
			if($column->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($column->getMessages() as $message)
				{
					echo $message;
				}
			}		
		}
		echo json_encode($status);	
	}
	
	public function updatecolumnAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
	
		
	
			$columns = array();
			$column = Column::findFirst('entity = "'.trim($this->entity,'/').'"');
			if(!isset($column->id))
			{
				$column = new Column(); 
			}
			$column->entity = trim($this->entity,'/');

			//save standard columns 
			foreach ($post as $key => $value)
			{
				if($key != 'entity' && $value > 0)
				{ 
					 array_push($columns,$key);
				}				
			}
	
			$column->columns = $columns;
			if($column->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($column->getMessages() as $message)
				{
					echo $message;
				}
			}		
		}
		echo json_encode($status);	
	}

    public function search($columns,$entity,$args = false)
    {
		$auth = $this->session->get('auth');
		$q = $this->request->getQuery('q');
		$query = array();
		$query['conditions'] = '';
		
		//search string
		if(strlen($q) > 0)
		{
			$cc = 0;$sq=' ( ';
			foreach($columns as $column)
			{
				$sq .= $column.' Like "%'.$q.'%" ';
				if(count($columns)-1> $cc){ $sq .= ' OR '; }
				$cc++;
			}	
			$sq .= ' ) ';
			$query['conditions'] = $sq;
		}
		
		if($args)
		{
			$sq = $query['conditions']; 
			if(strlen($sq) > 1){ $sq .= ' AND '; }
			$sq .= $args;
			$query['conditions'] = $sq;
		}

		//EVERY USER ONLY SEES WHAT THE USER IS ALLOWED TO SEE PROBABLY ONLY FOR FAMILY FACTORY
		if($auth['clearance'] < 900)
		{
			$ors = $this->authlist(strtolower($entity));
			$st = $query['conditions']; 
			if(strlen($st) > 1){ $st .= ' AND '; }
			if(strlen($ors) > 0)
			{ 
				$st .= ' ( '.$ors.' ) ';
			
				$query['conditions'] = $st;
			
				$items = $entity::find($query);	
			}
			else
			{
				$items = $entity::find(array('limit' => 0));
			}
		}
		else
		{
			$items = $entity::find($query);
		}
		
		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => $items,
				"limit"=> 20,
				"page" => $this->request->getQuery("p", "int", 1)
			)
		);

		// Get the paginated results
		$items = $paginator->getPaginate();
			
		return $items; 
    }

    public function uuid()
	{
	 return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
	}

    protected function forward($uri){
    	$uriParts = explode('/', $uri);
    	return $this->dispatcher->forward(
    		array(
    			'controller' => $uriParts[0], 
    			'action' => $uriParts[1]
    		)
    	);
    }

	public function handlefiles($fileid)
	{
		if(isset($post['files']))
		{
			return $post['files'][0];
		}				
	}
	
	public function autharray($entity)
	{
		//FAMILY FACTORY CUSTOM ACL FUNCTION
		$auth = $this->session->get('auth');
		$categories = Acl::find('userid = "'.$auth['id'].'" AND entity = "category"');
		$cats = array();
		$cc=0;
		//TODO PUSH THE ALSO TEAMS 
		foreach($categories as $cat)
		{	
			array_push($cats,$cat->entityid);	$cc++;	
		}
		return $cats;
	}
	
	public function authlist($entity)
	{
		//FAMILY FACTORY CUSTOM ACL FUNCTION
		$auth = $this->session->get('auth');	
		$categories = Acl::find('userid = "'.$auth['id'].'" AND entity = "category"');
		$cats = array();

		$cc=0;
		//TODO PUSH THE ALSO TEAMS 
		foreach($categories as $cat)
		{	
			array_push($cats,$cat->entityid);	$cc++;	
		}
		
		$Entity = ucfirst($entity);
		if($entity == 'category')
		{
			return $this->orquery('id',$cats);
		}
		else if($entity == 'emailmessage')
		{
			return array();
		}
		else if($entity == 'user')
		{
			//USERS ARE CONNECTED BY ACL
			$users = array();
			$rules = Acl::find('entity = "category" AND ( '.$this->orquery('entityid',$cats).' ) ');
			foreach($rules as $rule)
			{ array_push($users,$rule->userid); }
		
			//return users
			return $this->orquery('id',$users);
		}
		else
		{
			return $this->orquery('categoryid',$cats);
		}
	}
	
	
	
	public function check($entity)
	{
		$auth = $this->session->get('auth');

		//check ACL for allowed rules
		$allowed = array();
		$aclrules = Acl::find('userid = "'.$auth['id'].'" AND entity = "'.$entity.'"');
		foreach($aclrules as $rule)
		{ array_push($allowed,$rule->entityid);  }
		
		return $allowed;
	}
	public function orquery($name ,array $values)
	{
		$cc=0;
		$query = '';
		foreach($values as $value)
		{
			if($cc > 0){ $query .= ' OR '; }
			$query .= ' '.$name.' = "'.$value.'"';
			$cc++;
		}
		return $query;
	}
	
	
	
	
	
	

	protected function csv($entities,$columns,$entityname)
	{
		$string = '';
		//table headers
		$cc=0;
		$keys = array();
		$row = array();		

		$row[0] = array();
		foreach($entities->items[0] as $key => $value)
		{
			if(in_array($key,$columns))
			{ 
				array_push($keys,$key);
				array_push($row[0],$key);
			}
		}

		$string .= '\n';
		$cc=1;
		
		foreach($entities->items as $entity)
		{
			$row[$cc] = array();
			foreach($keys as $key)
			{				
				array_push($row[$cc],$entity->$key);					
			}
			$cc++;
		}

		$filename = '../../uploads/exports/'.$entityname.date('Y-m-d').'.csv';			
		if($f = fopen($filename, 'w'))
		{
			fwrite($f,$string);
			foreach ($row as $fields) 
			{
				fputcsv($f,$fields,';');
			}
			fclose($f);
		}
		return 'http://'.$_SERVER['HTTP_HOST'].'/MGMTx/uploads/exports/'.$entityname.date('Y-m-d').'.csv';
	}
	
	public function getfiles($entity,$id)
	{
		$Entity = ucfirst($entity);

		
		$entity = $Entity::findFirst('id = "'.$id.'"');
				
		$files = File::find('entityid = "'.$id.'"');
	
		if(count($files) > 0)
		{
			return $files;	
		}
		else if(isset($entity->id))
		{
			$files = array(File::findFirst('id = "'.$entity->fileid.'"')); 	
		}
		return $files;
	}
	
	protected function getmessages($messages)
	{
		$mess = array();
		for($i=0;$i<count($messages);$i++)
		{
			$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
			array_push($mess,$message);
		}
		return $mess;
	}
	
	protected function striptagsfilter($validation,$fields)
	{
		foreach($fields as $field)
		{
			$validation->setFilters($field, array("string", "striptags"));	
		}
		return $validation;
	}	
	
	protected function setcolumns($entity = false)
	{
		if(!$entity){ $entity = $this->entity; }	
	
		//ordered columns zorgt voor de volgorde van de kolommen	
		$table = Column::findFirst('entity = "'.$entity.'"');
		if(!$table)
		{
			$table = new Column();
		}
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array(); }
		$this->view->setVar("columns", $columns);
	
		//getallordered columns zorgt voor de volgorde de selectie weergave van de kolommen
		$Entity = ucfirst($entity);
		$allcolumns = array_keys($Entity::columnMap());
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	}
	
	protected function setaction($entity = false)
	{
		if(!$entity){ $entity = $this->entity; }
	
		$actions = $this->actionauth($entity);
		$this->view->setVar("actions", $actions);	
	}
	
	protected function getargs($post)
	{
		$args = array();
		//order is niet gevuld als er eerst word gezocht
		if(isset($post['order']) && strlen($post['order']) > 0)
		{ 
			$args['order'] = $post['order'];
		}
		//search is ook niet altijd gevuld
		if(isset($post['search']) && strlen($post['search']) > 0)
		{ 
			$args['search'] = $post['search']; 	
		}			
		return $args;
	}
	
	protected function getEntity($entity,$post)
	{
		$entity = ucfirst($entity);
		
		$new = false;
                if(isset($post['id'])){ $entityx = $entity::findFirst('id = "'.$post['id'].'"'); }
		if(!isset($userx->id))
		{ 		 
			$entity = new $entity();
			if(!isset($post['id']) || strlen($post['id']) < 5){ $entity->id = $this->uuid(); }else{ $entity->id = $post['id']; }
			$new = true;
		}
		else
		{
			$entity = $entityx;
		}
		$this->new = $new;
		return $entity;
	}
	
	public function updateAction()
	{
            $this->view->disable();
            $status = $this->status;
            if($this->request->isPost()) 
            {
                $post = $this->post;
                $e = ucfirst($this->entity);
                $entity = $e::findFirst('id = "'.$post['id'].'"');
                if($entity)
                {
                    $entity->$post['field'] = $post['html'];
                    if($entity->save())
                    {
                        $status['status'] = 'ok';
                    }
                }
                else
                {
                    array_push($status['messages'],'Entity not found');
                }
            }
            echo json_encode($status);
	}
	
	public function updatesettingsAction()
	{
		$this->view->disable();
		$status = $this->status;
		if($this->request->isPost()) 
		{
			$post = $this->post;
			$entity = MgmtEntity2::findFirst('name = "'.$this->entity.'"');	
			if($entity)
			{
				$entity->$post['key'] = $post['value'];
			}	
		}
		echo json_encode($status);
	}
}


