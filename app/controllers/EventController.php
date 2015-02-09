<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class EventController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Events');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Event');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "event"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("status"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');		
		$events = $this->search($columns,'Event'); 
		if(count($events) > 0){ $this->view->setVar("events", $events); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "event"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("status"); }
		$this->view->setVar("eventcolumns", $columns);	
		
		$allcolumns = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$events = $this->search($columns,'Event',$args);  
		if(count($events) > 0){ $this->view->setVar("indexevents", $events); }		
		
		
		$this->view->partial("event/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			
			$table = Column::findFirst('entity = "event"');
			$allcolumns = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("status"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("eventcolumns", $columns);	

			$args['entity'] = 'event';
			$args['columns'] = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
		
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
				$this->view->setVar("indexevents", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("event/clean"); 	
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
				$event = Event::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$event = Event::findFirst();
			}	
		
			$cm = $event->columnMap();
		
			$this->view->setVar("event", $event);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $event->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$event = Event::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($event as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($event->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($event->getMessages() as $message)
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

		$event = Event::findFirst('id = "'.$id.'"');
		
		$cm = $event->columnMap();
		
		$this->view->setVar("event", $event);
			
		//files
		$files = $this->getfiles('event',$event->id);		
		$this->view->setVar("files", $files);	

		

		//relation
		$tags = Tag::find();
		$this->view->setVar("tags", $tags);

		//relations
		$eventtags = EventTag::find('eventid = "'.$event->id.'"');
		$rtags = array();

		foreach($eventtags as $eventtag)
		{ array_push($rtags,$eventtag->tagid);	}
		$this->view->setVar("eventtags",$rtags);

		
	

		//relation
		$users = User::find();
		$this->view->setVar("users", $users);

		//relations
		$eventusers = EventUser::find('eventid = "'.$event->id.'"');
		$rusers = array();

		foreach($eventusers as $eventuser)
		{ array_push($rusers,$eventuser->userid);	}
		$this->view->setVar("eventusers",$rusers);

					
				
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $event->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
		//special field line controller.php line:95
		if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
		if($sessionuser['clearance'] < 900)
		{
			$q = $this->orquery('id',$this->check('category'));
			$resultset = Category::find(array('conditions' => $q));
		}
		else
		{
			$resultset = Category::find();
		}
		$this->view->setVar("categorys",$resultset);	
								

		
		

	
	
	/*
		THIS IS THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/	
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
		$emailcategories = Category::find();
		foreach($emailcategories as $cat)
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
	/*
		THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/
	
	
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("event/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$event = Event::findFirst();
		if(!isset($event->id))
		{
			$event = new Event();
			$event->userid = $session['id'];
		}
		
		$cm = $event->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
								//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('id',$this->check('category'));
									$resultset = Category::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Category::find();
								}
								$this->view->setVar("categorys",$resultset);	
									
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
			//special field line controller.php line:95
						if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
						if($sessionuser['clearance'] < 900)
						{
							$q = $this->orquery('entityid',$this->check('tag'));
							$resultset = Tag::find(array('conditions' => $q));
						}
						else
						{
							$resultset = Tag::find();
						}
						$this->view->setVar("tags",$resultset);	
						//special field line controller.php line:95
						if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
						if($sessionuser['clearance'] < 900)
						{
							$q = $this->orquery('category',$this->check('user'));
							$resultset = User::find(array('conditions' => $q));
						}
						else
						{
							$resultset = User::find();
						}
						$this->view->setVar("users",$resultset);	
					
		
		

	
	
	/*
		THIS IS THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/	
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
		$emailcategories = Category::find();
		foreach($emailcategories as $cat)
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("beschrijving", array("string", "striptags"));	$validation->setFilters("category", array("string", "striptags"));	$validation->setFilters("locatie", array("string", "striptags"));	$validation->setFilters("aanmeldingen", array("string", "striptags"));	$validation->setFilters("status", array("int", "striptags"));	 

			$validation->add('titel', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld titel is verplicht.'
			)));
			
			$validation->add('titel', new StringLength(array(
				'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('beschrijving', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld beschrijving is verplicht.'
			)));
			
			$validation->add('beschrijving', new StringLength(array(
				'messageMinimum' => 'Vul een beschrijving in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('start', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld start is verplicht.'
			)));
			
			$validation->add('einde', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld einde is verplicht.'
			)));
			
			$validation->add('category', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld category is verplicht.'
			)));
			
			$validation->add('category', new StringLength(array(
				'messageMinimum' => 'Vul een category in van tenminste 2 characters.',
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
				$eventx = Event::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($eventx->id))
				{ 		 
					$event = new Event();
					if(strlen($post['id']) < 5){ $event->id = $this->uuid(); }else{ $event->id = $post['id']; }
					$new = true;
				}
				else
				{
					$event = $eventx;
				}
				
				//save standard columns 
				foreach ($event as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$event->$key = $post[$key]; 
					}				
				}
				
				$event->userid = $this->user['id'];  
				$event->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
				if($new){ $event->creationdate = new Phalcon\Db\RawValue('now()');  } 
				else { $event->creationdate = date('Y-m-d H:i:s',strtotime($event->creationdate)); }
				$event->lastedit = new Phalcon\Db\RawValue('now()');   
				$event->start = date('Y-m-d H:i:s',strtotime($post['start']));
				$event->einde = date('Y-m-d H:i:s',strtotime($post['einde']));
				if(isset($post['files'])){ $event->fileid = $post['files'][0]; } 	

				if(isset($post['tags']) && is_array($post['tags']))
				{
					//delete previous choices
					foreach(EventTag::find('eventid = "'.$event->id.'"') as $eventtag)
					{	if($eventtag->delete() == false)
						{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
							array_push($status['messages'],$message);	}
					}		


					foreach($post['tags'] as $tag)
					{
						$eventtag = EventTag::findFirst('eventid = "'.$event->id.'" AND tagid = "'.$tag.'"');
						if(!isset($eventtag->id))
						{  
							$eventtag = new EventTag();	
							$eventtag->id = new Phalcon\Db\RawValue('uuid()');
							$eventtag->eventid = $event->id;
							$eventtag->tagid = $tag;
							if($eventtag->save())
							{ }
							else
							{
								foreach ($eventtag->getMessages() as $message)
								{
									//	echo $message;
								}
							}
						}		
					}
				}

				if(isset($post['users']) && is_array($post['users']))
				{
					//delete previous choices
					foreach(EventUser::find('eventid = "'.$event->id.'"') as $eventuser)
					{	if($eventuser->delete() == false)
						{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
							array_push($status['messages'],$message);	}
					}		
				
				
					foreach($post['users'] as $user)
					{
						$eventuser = EventUser::findFirst('eventid = "'.$event->id.'" AND userid = "'.$user.'"');
						if(!isset($eventuser->id))
						{  
							$eventuser = new EventUser();	
							$eventuser->id = new Phalcon\Db\RawValue('uuid()');
							$eventuser->eventid = $event->id;
							$eventuser->userid = $user;
							if($eventuser->save())
							{ }
							else
							{
								foreach ($eventuser->getMessages() as $message)
								{
									//	echo $message;
								}
							}
						}		
					}
				}
					
				$cc=0;	
				if($new)
				{
					$messages = $event->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
				
				/* CONNECT A TAG TO THIS ENTITY START */
				$tag = Tag::findFirst('entityid = "'.$post['id'].'"');
				if(!$tag)
				{
					$tag = new Tag();
					$tag->id = $this->uuid();
				}
				
				$tag->entityid = $post['id'];
				$tag->titel =  $post['titel'];
				$tag->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
				$tag->userid = $this->user['id'];  
				$tag->creationdate = new Phalcon\Db\RawValue('now()');   
				if($tag->save())
				{ }
				else
				{
					foreach ($tag->getMessages() as $message)
					{
						echo $message;
					}
				}

				/* CONNECT A TAG TO THIS ENTITY END */
				if($cc==0 && $event->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($event->getMessages() as $message)
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
			//EVENTS DIE NIET MEER BESTAAN HOREN OOK NIET IN DE TAGEVENTS TE STAAN
			$tag = Tag::findFirst('entityid = "'.$doelgroep->id.'"');
			foreach($tag->CategoryTag as $cattag)
			{
				if(!in_array($categories,$cattag->Category->parentid))
				{
					array_push($categories,$cattag->Category->parentid);	
					if($cc>0){ $q .= ' OR '; }
					$q .= ' id = "'.$cattag->Category->parentid.'" ';
					$cc++;
				}
			}
				
			$post = $this->request->getPost();
			$event = Event::findFirst('id = "'.$post['id'].'"');
			if($event->delete())
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

		$event = Event::findFirst('id = "'.$id.'"');
		$this->view->setVar("event", $event);
		$this->view->setVar("entityid", $event->id);
		
		$tabs = array();
		$this->view->setVar("tabs", $tabs);
		
		

	
	
	/*
		THIS IS THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/	
	$message = Message::findFirst('id = "event"');
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
		$emailcategories = Category::find();
		foreach($emailcategories as $cat)
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
	/*
		THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/
	
	
		
		
					
					//start event_tag stuff 	
			
					$actions = $this->actionauth('Tag');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "tag"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("tagcolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'EventTag';
					$args['args'] = 'eventid = "'.$event->id.'"';
					$args['search'] = false;
					$eventtags = $this->search2($args); 
					$indextags->items = array();
				
					//relation to entity
					foreach($eventtags->items as $eventtag)
					{ $indextags->items[] = $eventtag->Tag;	}
					$indextags->total_pages = $eventtags->total_pages;

					$this->view->setVar("indextags", $indextags);
					
					//end event_tag stuff
					
					
					//start event_user stuff 	
			
					$actions = $this->actionauth('User');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "user"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("usercolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'EventUser';
					$args['args'] = 'eventid = "'.$event->id.'"';
					$args['search'] = false;
					$eventusers = $this->search2($args); 
					$indexusers->items = array();
				
					//relation to entity
					foreach($eventusers->items as $eventuser)
					{ $indexusers->items[] = $eventuser->User;	}
					$indexusers->total_pages = $eventusers->total_pages;

					$this->view->setVar("indexusers", $indexusers);
					
					//end event_user stuff
					
		
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
			$allcolumns = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
			$table = Column::findFirst('entity = "event"');
			$columns = $table->columns;		

			$args['entity'] = 'event';
			$args['columns'] = array('titel','beschrijving','slug','creationdate','lastedit','start','einde','category','locatie','aanmeldingen','status');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$events = $this->search2($args); 

			if(count($events) > 0)
			{ 				
				$status['goto'] = $this->csv($events,$columns,'event');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
