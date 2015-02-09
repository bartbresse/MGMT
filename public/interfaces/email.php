	
	
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
	
	