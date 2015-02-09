<?php
use Phalcon\Forms\Form,
	Phalcon\Forms\FileUpload,
	Phalcon\Forms\TextEditor,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class TemplateController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('email');
        Phalcon\Tag::setTitle('Templates');
        parent::initialize();
		$this->entity = 'Template';
    }

    public function indexAction()
    {		
		/*
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Template');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "template"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("filename"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','filename');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','filename');		
		$templates = $this->search($columns,'Template',$args); 
		if(count($templates) > 0){ $this->view->setVar("templates", $templates); }
		*/
		
		$this->setaction();
		$this->setcolumns();
		$templates = $this->search2(array('columns' => array_keys(User::columnMap()),'entity' => $this->entity)); 
		if(count($templates) > 0){ $this->view->setVar("indextemplates", $templates); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		$this->setaction();
		$this->setcolumns();
	
		if(isset($post['field']) && isset($post['value']))
		{
			$acl = Acl::find('entityid = "'.$post['value'].'" ');
			$cc=0;$str = '';
			foreach($acl as $rule)
			{
				if($cc>0){$str .= ' OR '; }
				$str .= ' id = "'.$rule->userid.'" ';
				$cc++;
			}
			$args = $str;
		}
	
		$users = $this->search2(array('columns' => array_keys(User::columnMap()),'entity' => 'user','conditions' => $args)); 
		if(count($users) > 0)
		{ 
			$this->view->setVar("users", $users); 
		}		
		$this->view->partial("user/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('titel','slug','filename');
			$table = Column::findFirst('entity = "template"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("templatecolumns", $columns);	

			$args['entity'] = 'template';
			$args['columns'] = array('titel','slug','filename');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indextemplates", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("template/clean"); 	
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
				$template = Template::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$template = Template::findFirst();
			}	
		
			$cm = $template->columnMap();
		
			$this->view->setVar("template", $template);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $template->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$template = Template::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($template as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($template->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($template->getMessages() as $message)
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

		$template = Template::findFirst('id = "'.$id.'"');
		$cm = $template->columnMap();
		
		$this->view->setVar("template", $template);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $template->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			$form->add(new FileUpload('file',array('id'=>'file','class'=>'form-control','x' => 200,'y' => 200)));

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("template/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$template = Template::findFirst();
		if(!isset($template->id))
		{
			$template = new Template();
			$template->userid = $session['id'];
		}
		
		$cm = $template->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		$form->add(new FileUpload('file',array('id'=>'file','class'=>'form-control','x' => 200,'y' => 200)));
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("filename", array("string", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('filename', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld filename is verplicht.'
							)));
							
							$validation->add('filename', new StringLength(array(
								'messageMinimum' => 'Vul een filename in van tenminste 2 characters.',
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
				$templatex = Template::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($templatex->id))
				{ 		 
					$template = new Template();
					if(strlen($post['id']) < 5){ $template->id = $this->uuid(); }else{ $template->id = $post['id']; }
					$new = true;
				}
				else
				{
					$template = $templatex;
				}
				
				//save standard columns 
				foreach ($template as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$template->$key = $post[$key]; 
					}				
				}
				
				$template->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));		
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $template->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($template->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($template->getMessages() as $message)
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
			$template = Template::findFirst('id = "'.$post['id'].'"');
			if($template->delete())
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

		$template = Template::findFirst('id = "'.$id.'"');
		$this->view->setVar("template", $template);
		$this->view->setVar("entityid", $template->id);
		
		$tabs = array('message');
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					//start message stuff 
					
					$actions = $this->actionauth('message');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "message"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("messagecolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Message';
					$args['args'] = 'templateid = "'.$template->id.'"';
					$args['search'] = false;
					
					$indexmessages = $this->search2($args); 		
					$this->view->setVar("indexmessages", $indexmessages);
					
					//end message stuff
					
		
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
			$allcolumns = array('titel','slug','filename');
			$table = Column::findFirst('entity = "template"');
			$columns = $table->columns;		

			$args['entity'] = 'template';
			$args['columns'] = array('titel','slug','filename');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$templates = $this->search2($args); 

			if(count($templates) > 0)
			{ 				
				$status['goto'] = $this->csv($templates,$columns,'template');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
