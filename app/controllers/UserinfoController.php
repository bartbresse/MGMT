<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class UserinfoController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Userinfos');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Userinfo');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "userinfo"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("httpuseragent"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('httpreferer','remoteaddr','httpuseragent');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('httpreferer','remoteaddr','httpuseragent');		
		$userinfos = $this->search($columns,'Userinfo',$args); 
		if(count($userinfos) > 0){ $this->view->setVar("userinfos", $userinfos); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "userinfo"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("httpuseragent"); }
		$this->view->setVar("userinfocolumns", $columns);	
		
		$allcolumns = array('httpreferer','remoteaddr','httpuseragent');
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('httpreferer','remoteaddr','httpuseragent');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$userinfos = $this->search($columns,'Userinfo',$args); 
		if(count($userinfos) > 0){ $this->view->setVar("indexuserinfos", $userinfos); }		
		$this->view->partial("userinfo/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('httpreferer','remoteaddr','httpuseragent');
			$table = Column::findFirst('entity = "userinfo"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("userinfocolumns", $columns);	

			$args['entity'] = 'userinfo';
			$args['columns'] = array('httpreferer','remoteaddr','httpuseragent');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indexuserinfos", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("userinfo/clean"); 	
		}
	}

	public function templateAction() 
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$form = new Form();
		
			if(isset($post['id']))
			{	
				$userinfo = Userinfo::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$userinfo = Userinfo::findFirst();
			}	
		
			$cm = $userinfo->columnMap();
		
			$this->view->setVar("userinfo", $userinfo);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $userinfo->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

			//TODO add special fields
			$form->add(new Textarea("html", array("id" => "html","class" => $post['template']."-control")));
			$form->add(new Textarea("footer", array("id" => "footer","class" => $post['template']."-control")));

			$templates = Template::find();
			$this->view->setVar("templates", $templates);
			$this->view->setVar("form", $form);
		
			$entityid = $this->uuid();
			$this->view->setVar("entityid", $entityid);
			$this->view->partial("file/".$post['template']); 	
		}
	}

	public function updateAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();

			$userinfo = Userinfo::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($userinfo as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($userinfo->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($userinfo->getMessages() as $message)
				{
				//	echo $message;
				}
			}		
		}
		echo json_encode($status);
	}


	public function editAction()
	{
		$form = new Form();
		
		$id = $this->request->getQuery('id');

		$userinfo = Userinfo::findFirst('id = "'.$id.'"');
		$cm = $userinfo->columnMap();
		
		$this->view->setVar("userinfo", $userinfo);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $userinfo->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("userinfo/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$userinfo = Userinfo::findFirst();
		if(!isset($userinfo->id))
		{
			$userinfo = new Userinfo();
			$userinfo->userid = $session['id'];
		}
		
		$cm = $userinfo->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
				
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		
		
		
		
		$this->view->setVar("form", $form);
	}

	public function addAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();

			$validation = new Phalcon\Validation();
	
			$validation->setFilters("httpreferer", array("string", "striptags"));	$validation->setFilters("remoteaddr", array("string", "striptags"));	$validation->setFilters("httpuseragent", array("string", "striptags"));	 

			
							$validation->add('httpreferer', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld httpreferer is verplicht.'
							)));
							
							$validation->add('httpreferer', new StringLength(array(
								'messageMinimum' => 'Vul een httpreferer in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('remoteaddr', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld remoteaddr is verplicht.'
							)));
							
							$validation->add('remoteaddr', new StringLength(array(
								'messageMinimum' => 'Vul een remoteaddr in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('httpuseragent', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld httpuseragent is verplicht.'
							)));
							
							$validation->add('httpuseragent', new StringLength(array(
								'messageMinimum' => 'Vul een httpuseragent in van tenminste 2 characters.',
								'min' => 2
							)));
							

			$messages = $validation->validate($post);
			if (count($messages))
			{
				for($i=0;$i<count($messages);$i++){
				$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
				array_push($status['messages'],$message); }
			}
			else
			{
				$new = false;
				$userinfox = Userinfo::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($userinfox->id))
				{ 		 
					$userinfo = new Userinfo();
					if(strlen($post['id']) < 5){ $userinfo->id = $this->uuid(); }else{ $userinfo->id = $post['id']; }
					$new = true;
				}
				else
				{
					$userinfo = $userinfox;
				}
				
				//save standard columns 
				foreach ($userinfo as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$userinfo->$key = $post[$key]; 
					}				
				}
				
				 $userinfo->userid = $this->user['id'];  
								
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $userinfo->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($userinfo->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($userinfo->getMessages() as $message)
					{
						//	echo $message;
					}
				}		
			}
		}
		echo json_encode($status);
	}

	public function deleteAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$userinfo = Userinfo::findFirst('id = "'.$post['id'].'"');
			if($userinfo->delete())
			{
				$status['status'] = 'ok';
			}
		}
		echo json_encode($status);
	}

	public function viewAction()
	{
		$form = new Form();
	
		$id = $this->request->getQuery('id');

		$userinfo = Userinfo::findFirst('id = "'.$id.'"');
		$this->view->setVar("userinfo", $userinfo);
		$this->view->setVar("entityid", $userinfo->id);
		
		$tabs = array();
		$this->view->setVar("tabs", $tabs);
		
		
		
		
		
		$this->view->setVar("form", $form);
	}

	public function exportAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('httpreferer','remoteaddr','httpuseragent');
			$table = Column::findFirst('entity = "userinfo"');
			$columns = $table->columns;		

			$args['entity'] = 'userinfo';
			$args['columns'] = array('httpreferer','remoteaddr','httpuseragent');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$userinfos = $this->search2($args); 

			if(count($userinfos) > 0)
			{ 				
				$status['goto'] = $this->csv($userinfos,$columns,'userinfo');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
