<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class ContactController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Contacts');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Contact');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "contact"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("nieuwsbrief"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('naam','email','telefoon','bericht','post','nieuwsbrief');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('naam','email','telefoon','bericht','post','nieuwsbrief');		
		$contacts = $this->search($columns,'Contact'); 
		if(count($contacts) > 0){ $this->view->setVar("contacts", $contacts); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "contact"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("nieuwsbrief"); }
		$this->view->setVar("contactcolumns", $columns);	
		
		$allcolumns = array('naam','email','telefoon','bericht','post','nieuwsbrief');
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('naam','email','telefoon','bericht','post','nieuwsbrief');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$contacts = $this->search($columns,'Contact',$args); 
		if(count($contacts) > 0){ $this->view->setVar("indexcontacts", $contacts); }		
		$this->view->partial("contact/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('naam','email','telefoon','bericht','post','nieuwsbrief');
			$table = Column::findFirst('entity = "contact"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("contactcolumns", $columns);	

			$args['entity'] = 'contact';
			$args['columns'] = array('naam','email','telefoon','bericht','post','nieuwsbrief');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indexcontacts", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("contact/clean"); 	
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
				$contact = Contact::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$contact = Contact::findFirst();
			}	
		
			$cm = $contact->columnMap();
		
			$this->view->setVar("contact", $contact);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $contact->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$contact = Contact::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($contact as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($contact->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($contact->getMessages() as $message)
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

		$contact = Contact::findFirst('id = "'.$id.'"');
		$cm = $contact->columnMap();
		
		$this->view->setVar("contact", $contact);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $contact->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("bericht", array("id" => "bericht","class" => "form-control")));
								

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("contact/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$contact = Contact::findFirst();
		if(!isset($contact->id))
		{
			$contact = new Contact();
			$contact->userid = $session['id'];
		}
		
		$cm = $contact->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("bericht", array("id" => "bericht","class" => "form-control")));
									
		
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
	
			$validation->setFilters("naam", array("string", "striptags"));	$validation->setFilters("email", array("string", "striptags"));	$validation->setFilters("telefoon", array("string", "striptags"));	$validation->setFilters("bericht", array("string", "striptags"));	$validation->setFilters("nieuwsbrief", array("int", "striptags"));	 

			$validation->add('email', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld email is verplicht.'
			)));
			
			$validation->add('email', new StringLength(array(
				'messageMinimum' => 'Vul een email in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('bericht', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld bericht is verplicht.'
			)));
			
			$validation->add('bericht', new StringLength(array(
				'messageMinimum' => 'Vul een bericht in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('post', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld post is verplicht.'
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
				$contactx = Contact::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($contactx->id))
				{ 		 
					$contact = new Contact();
					if(strlen($post['id']) < 5){ $contact->id = $this->uuid(); }else{ $contact->id = $post['id']; }
					$new = true;
				}
				else
				{
					$contact = $contactx;
				}
				
				//save standard columns 
				foreach ($contact as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$contact->$key = $post[$key]; 
					}				
				}
				
				$event->post = date('Y-m-d H:i:s',strtotime($post['post']));
							 $contact->userid = $this->user['id'];  
								
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $contact->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($contact->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($contact->getMessages() as $message)
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
			$contact = Contact::findFirst('id = "'.$post['id'].'"');
			if($contact->delete())
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

		$contact = Contact::findFirst('id = "'.$id.'"');
		$this->view->setVar("contact", $contact);
		$this->view->setVar("entityid", $contact->id);
		
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
			$allcolumns = array('naam','email','telefoon','bericht','post','nieuwsbrief');
			$table = Column::findFirst('entity = "contact"');
			$columns = $table->columns;		

			$args['entity'] = 'contact';
			$args['columns'] = array('naam','email','telefoon','bericht','post','nieuwsbrief');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$contacts = $this->search2($args); 

			if(count($contacts) > 0)
			{ 				
				$status['goto'] = $this->csv($contacts,$columns,'contact');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
