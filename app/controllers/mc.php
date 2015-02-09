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
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Messages');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$actions = $this->actionauth('Message');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "message"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("tags"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');		
		$messages = $this->search($columns,'Message'); 
		if(count($messages) > 0){ $this->view->setVar("messages", $messages); }
	}



	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
			$table = Column::findFirst('entity = "message"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("columns", $columns);	

			$args['entity'] = 'message';
			$args['columns'] = array('titel','slug','subject','html','to','bcc','footer','lastedit','creationdate','tags');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("messages", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);

			$this->view->partial("message/index"); 	
		}
	}



	/* widget actions */
	/* widget actions */
	/* widget actions */
	/* widget actions */

	#startuserspecific#


	/*

		
	*/
	public function createtable()
	{
		
	}

	public function getgroups()
	{
		/*
		$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);

		/* landelijk */
		/* plaats */			
		/* team */

		/*
		$linktables = array();
		foreach ($tablesq as $tableq) 
		{
			//array_push($linktables,$tableq['Tables_in_'.DATABASENAME]);	
			$columnx = $this->db->fetchAll("DESCRIBE `".$tablex['Tables_in_'.DATABASENAME]."`", Phalcon\Db::FETCH_ASSOC);
			foreach($columnx as $column)
			{
				if('userid' == $column['Field'])
				{
					
				}
			}			
		}
		*/

		$groups = array('Zeewolde','Oosterhout','Amsterdam','Basisscholen - Zeewolde');
		return $groups;
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
			{ $message = Message::findFirst('id = "'.$post['id'].'"'); }
			else
			{ $message = Message::findFirst(); }
		
			$cm = $message->columnMap();
			$this->view->setVar("message", $message);
		
			$users = User::find();
			$this->view->setVar("users", $users);

			$groups = $this->getgroups();
			$this->view->setVar("groups", $groups);

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

	public function sendAction()
	{
		/* send email custom function */		
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$validation = new Phalcon\Validation();
	
			$validation->setFilters("html", array("string", "striptags"));	
			$validation->setFilters("subject", array("string", "striptags"));	
			$validation->setFilters("from_email", array("string", "striptags"));	
			$validation->setFilters("from_name", array("string", "striptags"));	
			$validation->setFilters("to", array("string", "striptags"));	 

			$validation->add('html', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld html is verplicht.'
			)));
			$validation->add('html', new StringLength(array(
				'messageMinimum' => 'Vul een html in van tenminste 2 characters.',
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
			
			$validation->add('from_email', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld from_email is verplicht.'
			)));
			$validation->add('from_email', new StringLength(array(
				'messageMinimum' => 'Vul een from_email in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('from_name', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld from_name is verplicht.'
			)));
			$validation->add('from_name', new StringLength(array(
				'messageMinimum' => 'Vul een from_name in van tenminste 2 characters.',
				'min' => 2
			)));

			$validation->add('to', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld to is verplicht.'
			)));
			$validation->add('to', new StringLength(array(
				'messageMinimum' => 'Vul een to in van tenminste 2 characters.',
				'min' => 2
			)));
							
			$messages = $validation->validate($post);
			if(count($messages))
			{
				for($i=0;$i<count($messages);$i++){
				$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
				array_push($status['messages'],$message); }
			}
			else
			{
				$emailx = Emailmessage::findFirst('id = "'.$post['entityid'].'"');  
				if(!isset($emailx->id))
				{ 		 
					$email = new Emailmessage();
					if(!isset($post['id']) || strlen($post['id']) < 5){ $email->id = $this->uuid(); }else{ $email->id = $post['id']; }
				}
				else
				{
					$email = $emailx;
				}
				
				//save standard columns 
				foreach ($email as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$email->$key = $post[$key]; 
					}				
				}
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $email->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
	
				$email->to = array(array('email' => $post['to']));

				if($email->send() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach($email->getMessages() as $message)
					{
						//	echo $message;
					}
				}		
			}
		}
		echo json_encode($status);
	}
	#enduserspecific#



	/* widget actions */
	/* widget actions */
	/* widget actions */
	/* widget actions */

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

		$templates = Template::find();
		$this->view->setVar("templates", $templates);
								
								
								

		$this->view->setVar("form", $form);
		$this->view->pick("message/new");	
	}

	public function newAction()
	{
		$form = new Form();
		
		$message = Message::findFirst();
		if(!isset($message->id))
		{
			$message = new Message();
		}
		
		$cm = $message->columnMap();
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("html", array("id" => "html","class" => "form-control")));
							$form->add(new Textarea("footer", array("id" => "footer","class" => "form-control")));
							
								$templates = Template::find();
								$this->view->setVar("templates", $templates);
								
								
									

		$this->view->setVar("form", $form);
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		
		
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
			
			$validation->add('to', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld to is verplicht.'
			)));
			
			$validation->add('to', new StringLength(array(
				'messageMinimum' => 'Vul een to in van tenminste 2 characters.',
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
			
				$messagex = Message::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($messagex->id))
				{ 		 
					$message = new Message();
					if(strlen($post['id']) < 5){ $message->id = $this->uuid(); }else{ $message->id = $post['id']; }
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
				
				$message->slug = preg_replace("/[^a-zA-Z]/", "", $post['titel']); $message->lastedit = new Phalcon\Db\RawValue('now()');   
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
		$id = $this->request->getQuery('id');

		$message = Message::findFirst('id = "'.$id.'"');
		$this->view->setVar("message", $message);

		$tabs = array();

		$this->view->setVar("tabs", $tabs);
		
		
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


	#startuserspecific##enduserspecific#

}
?>
