<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class EmailwidgetController extends ControllerBase
{
	private $entity;
	private $Entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Emailwidget');
        parent::initialize();
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'user';
		$this->Entity = 'User';	
	}

	
	private function setcolumns($entity)
	{
		//ordered columns zorgt voor de volgorde van de kolommen	
		$table = Column::findFirst('entity = "'.$entity.'"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("verification"); }
		$this->view->setVar("columns", $columns);
	
		//getallordered columns zorgt voor de volgorde de selectie weergave van de kolommen
		$Entity = ucfirst($entity);
		$allcolumns = array_keys($Entity::columnMap());
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	}
	
	private function setaction($entity)
	{
		$actions = $this->actionauth($entity);
		$this->view->setVar("actions", $actions);	
	}

    public function indexAction()
    {	
	
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		$form = new Form();
		
		$message = Message::findFirst('id = "#id#"');
		if(!$message){	$message = new Message(); }

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
				array_push($groups,'categorie:'.$cat->titel);
			}
			
			$events = Event::find();
			foreach($events as $event)
			{
				array_push($groups,'evenement:'.$event->titel);	
			}
			$this->view->setVar('groups',$groups);
		}
		
		$this->view->partial("emailwidget/clean"); 			
	}
	
	public function addAction()
	{
	
	
	
			//TODO FINISH ADD WIDGET
			/*
					START CONTROLLER EMAIL INTERFACE
				*/
				if($new)
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
					
					$emailmessage->to = serialize($post['email-to']);
					
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
					if($emailmessage->send($post['email-to']) && $cc==0)
					{
						if($emailmessage->save())
						{
							$status['status'] = 'ok';	
						}
						else
						{
							foreach ($emailmessage->getMessages() as $message)
							{
								echo 'emailmessage:'.$message.'';
							} 
						}
					}
				}
				/*
					END CONTROLLER EMAIL INTERFACE
				*/
	}
}
