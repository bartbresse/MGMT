<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class EmailmessageController extends ControllerBase
{
    public function initialize()
    {
		parent::initialize();
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle($this->lang->translate('Email'));
    }

    public function indexAction()
    {			
		$actions = $this->actionauth('Emailmessage');
		
		//getallordered columns zorgt voor de volgorde de selectie weergave van de kolommen
		$table = Column::findFirst('entity = "emailmessage"');
		$allcolumns = array('html','subject','from_email','from_name','to');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
	
		//ordered columns zorgt voor de volgorde van de kolommen	
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("to"); }
		
		$this->view->setVar("actions", $actions);
		$this->view->setVar("allcolumns", $allcolumns);	
		$this->view->setVar("columns", $columns);
	
		$columns = array('html','subject','from_email','from_name','to');		
		$emailmessages = $this->search($columns,'Emailmessage'); 
		if(count($emailmessages) > 0){ $this->view->setVar("emailmessages", $emailmessages); }
	}

	public function scheduleAction()
	{
		
	}
	
	public function loadAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$message = Message::findFirst('id = "'.$post['templateid'].'"');
			
			echo $message->html;
		}
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('html','subject','from_email','from_name','to');
			$table = Column::findFirst('entity = "emailmessage"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("emailmessagecolumns", $columns);	

			$args['entity'] = 'emailmessage';
			$args['columns'] = array('html','subject','from_email','from_name','to');
			
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
				$this->view->setVar("indexemailmessages", $users); 
			}

			//sort post. used to remember the sorted column
			
			$this->view->setvar("post", $post);
			$this->view->partial("emailmessage/clean"); 	
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
				$emailmessage = Emailmessage::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$emailmessage = Emailmessage::findFirst();
			}	
		
			$cm = $emailmessage->columnMap();
		
			$this->view->setVar("emailmessage", $emailmessage);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $emailmessage->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$emailmessage = Emailmessage::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($emailmessage as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}
			
			if($emailmessage->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($emailmessage->getMessages() as $message)
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

		$emailmessage = Emailmessage::findFirst('id = "'.$id.'"');
		$cm = $emailmessage->columnMap();
		
		$this->view->setVar("emailmessage", $emailmessage);
		$this->view->setVar("message", $emailmessage);

		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $emailmessage->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("email-html", array("id" => "email-html","value" => $emailmessage->html,"class" => "form-control")));
		$form->add(new Text("email-from_email", array("id" => "email-from_email","value" => $emailmessage->from_email,"class" => "form-control","value" => "noreply@hetworks.nl")));
		$form->add(new Text("email-from_name", array("id" => "email-from_name","value" => $emailmessage->from_name,"class" => "form-control","value" => "HETWORKS MGMT")));
		$form->add(new Text("email-subject", array('value' => $emailmessage->subject , "id" => "email-subject","class" => "form-control")));
		
		$templates = Template::find();
		$this->view->setVar("templates", $templates);
		$this->view->setVar("form", $form);

		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		$users = User::find();
		$this->view->setVar('users',$users);
		
		//look for users that are relevant to this view
		$selecteduser = '';
		if(isset($user))
		{
			$selecteduser = $user->id;
		}
		$this->view->setVar('selecteduser',$selecteduser);
		
		if(!isset($groups))
		{
			$selectedgroups = array();
			//look for events or groups that are connected to this view
			if(isset($event))
			{
				array_push($selectedgroups,'evenement:'.$event->titel);
			}
			if(isset($category))
			{
				array_push($selectedgroups,'categorie:'.$category->titel);
			}
			$this->view->setVar('selectedgroups',$selectedgroups);
		
			$groups = array();
			//GROUPS ARE CATEGORIES AND EVENTS ATTENDEES
			$category = Category::find();
			foreach($category as $cat)
			{
				if($cat->niveau == 2)
				{
					array_push($groups,'plaats:'.$cat->titel);
				}
				else
				{
					array_push($groups,'team:'.$cat->titel);
				}
			}
			
			$events = Event::find();
			foreach($events as $event)
			{
				array_push($groups,'evenement:'.$event->titel);	
			}
			$this->view->setVar('groups',$groups);
		}
		/*
			THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
		*/
		
		$this->view->setVar("form", $form);
		$this->view->pick("emailmessage/new");	
	}

	#startuserspecific#
	public function sendAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();

			$validation = new Phalcon\Validation();
			$validation->setFilters("html", array("string", "striptags"));	$validation->setFilters("subject", array("string", "striptags"));	$validation->setFilters("from_email", array("string", "striptags"));	$validation->setFilters("from_name", array("string", "striptags"));	$validation->setFilters("to", array("string", "striptags"));	
			$validation->add('email-html', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het bericht veld is verplicht.'
			)));
			
			$validation->add('email-html', new StringLength(array(
				'messageMinimum' => 'Vul een bericht in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('email-subject', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld onderwerp is verplicht.'
			)));
			
			$validation->add('email-subject', new StringLength(array(
				'messageMinimum' => 'Vul een onderwerp in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$messages = $validation->validate($post);
		
			if (count($messages))
			{
				for($i=0;$i<count($messages);$i++)
				{
					$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
					array_push($status['messages'],$message); 
				}
			}
			else
			{
				$emailmessagex = Emailmessage::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($emailmessagex->id))
				{ 		 
					$emailmessage = new Emailmessage();
					if(strlen($post['id']) < 5){ $emailmessage->id = $this->uuid(); }else{ $emailmessage->id = $post['id']; }
				}
				else
				{
					$emailmessage = $emailmessagex;
				}
				
				//save standard columns 
				foreach ($emailmessage as $key => $value)
				{
					if(isset($post['email-'.$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$emailmessage->$key = $post['email-'.$key]; 
					}				
				}
				
				
			
				//turn categories,events,places and teams into an email list
				$emailadresses = array();
				
			
				
				if(is_array($post['email-to']) && count($post['email-to']) > 0)
				{
					foreach($post['email-to'] as $to)
					{
						$email = explode(':',$to);
						switch($email[0])
						{
							case 'plaats':
								$plaats = Category::findFirst(" slug = '".$email[1]."' OR titel = '".$email[1]."'");
								$teams = Category::find("parentid = '".$plaats->id."'");
								$rules = Acl::find('entityid = "'.$plaats->id.'"');
								
								foreach($rules as $rule)
								{
									$user = User::findFirst('id = "'.$rule->userid.'"');
									if(!in_array($user->email,$emailadresses))
									{
										array_push($emailadresses,array('email' => $user->email,'name' => $user->firstname.' '.$user->insertion.' '.$user->lastname,'type' => 'bcc'));
									}
								}
								
								foreach($teams as $team)
								{
									$rules = Acl::find('entityid = "'.$team->id.'"');
									foreach($rules as $rule)
									{
										$user = User::findFirst('id = "'.$rule->userid.'"');
										if(!in_array($user->email,$emailadresses))
										{
											array_push($emailadresses,array('email' => $user->email,'name' => $user->firstname.' '.$user->insertion.' '.$user->lastname,'type' => 'bcc'));
										}
									}
								}
								break;
							case 'team':
								$rules = Acl::find('entityid = "'.$email[1].'"');
								foreach($rules as $rule)
								{
									$user = User::findFirst('id = "'.$rule->userid.'"');
								
									if(!in_array($user->email,$emailadresses))
									{
										array_push($emailadresses,array('email' => $user->email,'name' => $user->firstname.' '.$user->insertion.' '.$user->lastname,'type' => 'bcc'));
									}
								}
								break;
							case 'evenement':
								$rules = Acl::find('entityid = "'.$email[1].'"');
								foreach($rules as $rule)
								{
									$user = User::findFirst('id = "'.$rule->userid.'"');
								
									if(!in_array($user->email,$emailadresses))
									{
										array_push($emailadresses,array('email' => $user->email,'name' => $user->firstname.' '.$user->insertion.' '.$user->lastname,'type' => 'bcc'));
									}
								}
								break;
							default:
								//	$user = User::findFirst(array('conditions' => 'email = "'.$email[0].'"'));
									
								array_push($emailadresses,array('email' => $email[0],'type' => 'bcc'));

								//case 'workshop':
								//	break;
						}
					}
				}
				else
				{
					$message['email-to'] = 'U bent vergeten een ontvanger te selecteren';
					array_push($status['messages'],$message); 
				}
				
				// email-messageid FILL A BCC IN EMAIL TEMPLATES
				$template = Message::findFirst('id = "'.$post['email-messageid'].'"');
				if($template)
				{
					$bcc = explode(',',$template->bcc);
					foreach($bcc as $b)
					{
						array_push($emailadresses,array('email' => $b,'type' => 'bcc'));
					}
				}
				
				$emailmessage->to = serialize($emailadresses);
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $emailmessage->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
			
				//EMAIL CALLS SEND HERE NOT SAVE
				if($cc==0 && $emailmessage->send($emailadresses,1))
				{
					if($emailmessage->save())
					{
						$status['status'] = 'ok';	
					}
					else
					{
						foreach($emailmessage->getMessages() as $message)
						{
							echo $message;
						} 
					}
				}
			}
		}
		echo json_encode($status);
	}
	#enduserspecific#
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$emailmessage = Emailmessage::findFirst();
		if(!isset($emailmessage->id))
		{
			$emailmessage = new Emailmessage();
			$emailmessage->userid = $session['id'];
		}
		
		$cm = $emailmessage->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("html", array("id" => "html","class" => "form-control")));
		$form->add(new Textarea("to", array("id" => "to","class" => "form-control")));
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		/*
			THIS IS THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
		*/	
		
		$message = Message::findFirst('id = "72559491-9d9e-4733-b0c8-d3f064c42284"');
		if(!$message)
		{	$message = new Message();  }
		
		$cm = $message->columnMap();
		
		$this->view->setVar("message", $message);

		$emailmessages = Message::find();
		$this->view->setVar("emailmessages", $emailmessages);

		foreach($cm as $column)
		{ $form->add(new Text('email-'.$column, array('value' => $message->$column,'id' => 'email-'.$column,'class' => 'form-control'))); }	
		//TODO add special fields
		$form->add(new Textarea("email-html", array("id" => "email-html","class" => "form-control")));
		$form->add(new Text("email-from_email", array("id" => "email-from_email","class" => "form-control","value" => "noreply@hetworks.nl")));
		$form->add(new Text("email-from_name", array("id" => "email-from_name","class" => "form-control","value" => "HETWORKS MGMT")));
		$form->add(new Text("email-subject", array('value' => $message->subject , "id" => "email-subject","class" => "form-control")));
		
		$templates = Template::find();
		$this->view->setVar("templates", $templates);
		$this->view->setVar("form", $form);

		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		$users = User::find();
		$this->view->setVar('users',$users);
		
		//look for users that are relevant to this view
		$selecteduser = '';
		if(isset($user))
		{
			$selecteduser = $user->id;
		}
		$this->view->setVar('selecteduser',$selecteduser);
		
		if(!isset($groups))
		{
			$selectedgroups = array();
			//look for events or groups that are connected to this view
			if(isset($event))
			{
				array_push($selectedgroups,'evenement:'.$event->titel);
			}
			if(isset($category))
			{
				array_push($selectedgroups,'categorie:'.$category->titel);
			}
			$this->view->setVar('selectedgroups',$selectedgroups);
		
			$groups = array();
			//GROUPS ARE CATEGORIES AND EVENTS ATTENDEES
			$category = Category::find();
			foreach($category as $cat)
			{
				if($cat->niveau == 2)
				{
					array_push($groups,'plaats:'.$cat->titel);
				}
				else
				{
					array_push($groups,'team:'.$cat->titel);
				}
			}
			
			$events = Event::find();
			foreach($events as $event)
			{
				array_push($groups,'evenement:'.$event->titel);	
			}
			$this->view->setVar('groups',$groups);
		}
		/*
			THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
		*/
		
		
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
	
			$validation->setFilters("html", array("string", "striptags"));	$validation->setFilters("subject", array("string", "striptags"));	$validation->setFilters("from_email", array("string", "striptags"));	$validation->setFilters("from_name", array("string", "striptags"));	$validation->setFilters("to", array("string", "striptags"));	 

			
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
			if (count($messages))
			{
				for($i=0;$i<count($messages);$i++){
				$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
				array_push($status['messages'],$message); }
			}
			else
			{
			
				$emailmessagex = Emailmessage::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($emailmessagex->id))
				{ 		 
					$emailmessage = new Emailmessage();
					if(strlen($post['id']) < 5){ $emailmessage->id = $this->uuid(); }else{ $emailmessage->id = $post['id']; }
				}
				else
				{
					$emailmessage = $emailmessagex;
				}
				
				//save standard columns 
				foreach ($emailmessage as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$emailmessage->$key = $post[$key]; 
					}				
				}
				
						
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $emailmessage->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

				if($emailmessage->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($emailmessage->getMessages() as $message)
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
			$emailmessage = Emailmessage::findFirst('id = "'.$post['id'].'"');
			if($emailmessage->delete())
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

		$emailmessage = Emailmessage::findFirst('id = "'.$id.'"');
		$this->view->setVar("emailmessage", $emailmessage);
		$this->view->setVar("entityid", $emailmessage->id);
		
		$tabs = array();
		$this->view->setVar("tabs", $tabs);
		$this->view->setVar("form", $form);
		
		//EMAIL STATISTICS STUFF 
		
		
		
	}

	public function exportAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('html','subject','from_email','from_name','to');
			$table = Column::findFirst('entity = "emailmessage"');
			$columns = $table->columns;		

			$args['entity'] = 'emailmessage';
			$args['columns'] = array('html','subject','from_email','from_name','to');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$emailmessages = $this->search2($args); 

			if(count($emailmessages) > 0)
			{ 				
				$status['goto'] = $this->csv($emailmessages,$columns,'emailmessage');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}




					

}
?>
