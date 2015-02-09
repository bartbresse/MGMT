<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class MessageController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('email');
        Phalcon\Tag::setTitle('Messages');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Message');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "message"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("tags"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');		
		$messages = $this->search($columns,'Message'); 
		if(count($messages) > 0){ $this->view->setVar("messages", $messages); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "message"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("tags"); }
		$this->view->setVar("messagecolumns", $columns);	
		
		$allcolumns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$messages = $this->search($columns,'Message',$args); 
		if(count($messages) > 0){ $this->view->setVar("indexmessages", $messages); }		
		$this->view->partial("message/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "message"');
			$allcolumns = array('titel','slug','subject','html','to','bcc','lastedit','creationdate','tags');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("tags"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("messagecolumns", $columns);	

			$args['entity'] = 'message';
			$args['columns'] = array('titel','slug','subject','html','to','bcc','lastedit','creationdate','tags');
			

			//order is niet gevuld als er eerst word gezocht
			if(isset($post['order']) && strlen($post['order']) > 0)
			{ 
				$args['order'] = $post['order']; 
				$sort = explode(' ',$post['order']);
				$post['key'] = $sort[0];
				$post['order'] = $sort[1];
			}
			
			//search is ook niet altijd gevuld
			if(isset($post['search']) && strlen($post['search']) > 0)
			{ 
				$args['search'] = $post['search']; 	
			}						
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indexmessages", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("message/clean"); 	
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
				$message = Message::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$message = Message::findFirst();
			}	
		
			$cm = $message->columnMap();
		
			$this->view->setVar("message", $message);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $message->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$message = Message::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($message as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($message->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($message->getMessages() as $message)
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

		$message = Message::findFirst('id = "'.$id.'"');
		$cm = $message->columnMap();
		
		$this->view->setVar("message", $message);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $message->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("html", array("id" => "html","class" => "form-control")));
							$form->add(new Textarea("footer", array("id" => "footer","class" => "form-control")));
								//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('',$this->check('template'));
									$resultset = Template::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Template::find();
								}
								$this->view->setVar("templates",$resultset);	
								

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("message/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$message = Message::findFirst();
		if(!isset($message->id))
		{
			$message = new Message();
			$message->userid = $session['id'];
		}
		
		$cm = $message->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("html", array("id" => "html","class" => "form-control")));
							$form->add(new Textarea("footer", array("id" => "footer","class" => "form-control")));
								//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('',$this->check('template'));
									$resultset = Template::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Template::find();
								}
								$this->view->setVar("templates",$resultset);	
									
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("subject", array("string", "striptags"));	$validation->setFilters("html", array("string", "striptags"));	$validation->setFilters("to", array("string", "striptags"));	$validation->setFilters("bcc", array("string", "striptags"));	$validation->setFilters("footer", array("string", "striptags"));	$validation->setFilters("tags", array("string", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('subject', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld subject is verplicht.'
							)));
							
							$validation->add('subject', new StringLength(array(
								'messageMinimum' => 'Vul een subject in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('html', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld html is verplicht.'
							)));
							
							$validation->add('html', new StringLength(array(
								'messageMinimum' => 'Vul een html in van tenminste 2 characters.',
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
				$messagex = Message::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($messagex->id))
				{ 		 
					$message = new Message();
					if(strlen($post['id']) < 5){ $message->id = $this->uuid(); }else{ $message->id = $post['id']; }
					$new = true;
				}
				else
				{
					$message = $messagex;
				}
				
				//save standard columns 
				foreach ($message as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$message->$key = $post[$key]; 
					}				
				}
				
				$message->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); $message->lastedit = new Phalcon\Db\RawValue('now()');   
							if(!isset($messagex->id)){ $message->creationdate = new Phalcon\Db\RawValue('now()');  } 
									
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $message->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($message->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($message->getMessages() as $message)
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
			$message = Message::findFirst('id = "'.$post['id'].'"');
			if($message->delete())
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

		$message = Message::findFirst('id = "'.$id.'"');
		$this->view->setVar("message", $message);
		$this->view->setVar("entityid", $message->id);
		
		$tabs = array('emailmessage');
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					//start emailmessage stuff 
					
					$actions = $this->actionauth('emailmessage');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "emailmessage"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("emailmessagecolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Emailmessage';
					$args['args'] = 'messageid = "'.$message->id.'"';
					$args['search'] = false;
					
					$indexemailmessages = $this->search2($args); 		
					$this->view->setVar("indexemailmessages", $indexemailmessages);
					
					//end emailmessage stuff
					
		
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
			$allcolumns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
			$table = Column::findFirst('entity = "message"');
			$columns = $table->columns;		

			$args['entity'] = 'message';
			$args['columns'] = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$messages = $this->search2($args); 

			if(count($messages) > 0)
			{ 				
				$status['goto'] = $this->csv($messages,$columns,'message');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#
	public function loadAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$html = '';
			$post = $this->request->getPost();
			$template = Template::findFirst('id = "'.$post['templateid'].'"');
			if($template)
			{
				$contents =	file_get_contents(BASEURL.'backend/templates/'.$template->filename);
				$a = array('#entity#');
				$b = array('text');
				echo str_replace($a,$b,$contents);
			}
			else
			{
				$status = array('status' => 'false','message' => 'Template not found.');
				echo json_encode($status);		
			}
		}
	}
	#enduserspecific#

}
?>
