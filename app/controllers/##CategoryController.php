<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class CategoryController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Categorys');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$actions = $this->actionauth('Category');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "category"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("locatiey"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');		
		$categorys = $this->search($columns,'Category'); 
		if(count($categorys) > 0){ $this->view->setVar("categorys", $categorys); }
	}

	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');
			$table = Column::findFirst('entity = "category"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("columns", $columns);	

			$args['entity'] = 'category';
			$args['columns'] = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("categorys", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("category/index"); 	
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
				$category = Category::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$category = Category::findFirst();
			}	
		
			$cm = $category->columnMap();
		
			$this->view->setVar("category", $category);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $category->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$category = Category::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($category as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($category->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($category->getMessages() as $message)
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

		$category = Category::findFirst('id = "'.$id.'"');
		$cm = $category->columnMap();
		
		$this->view->setVar("category", $category);
		
		
			
					//files
					$files = $this->getfiles('category',$category->id);		
					$this->view->setVar("files", $files);	
			
					
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $category->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving1", array("id" => "beschrijving1","class" => "form-control")));
							$form->add(new Textarea("beschrijving2", array("id" => "beschrijving2","class" => "form-control")));
							
								$paginas = Pagina::find();
								$this->view->setVar("paginas", $paginas);
								
								

		
		

if(!isset($allusers))
{
	$alluser = new Users();
	$this->views->setvar('relaties_allusers',$allusers);
}

if(!isset($form))
{
	$form = new Form();
}

$form->add(new Select('relaties_beheerder',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_beheerder','class' => 'form-control relaties-beheerder-multiple-select','data-placeholder' => 'Kies een beheerder')));
$form->add(new Select('relaties_leden',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_leden','class' => 'form-control relaties-leden-multiple-select','data-placeholder' => 'Kies hier uw leden.')));


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
	

	/*
		THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/

if(!isset($allusers))
{
	$alluser = new Users();
	$this->views->setvar('relaties_allusers',$allusers);
}

if(!isset($form))
{
	$form = new Form();
}

$form->add(new Select('relaties_beheerder',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_beheerder','class' => 'form-control relaties-beheerder-multiple-select','data-placeholder' => 'Kies een beheerder')));
$form->add(new Select('relaties_leden',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_leden','class' => 'form-control relaties-leden-multiple-select','data-placeholder' => 'Kies hier uw leden.')));



		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("category/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$category = Category::findFirst();
		if(!isset($category->id))
		{
			$category = new Category();
			$category->userid = $session['id'];
		}
		
		$cm = $category->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving1", array("id" => "beschrijving1","class" => "form-control")));
							$form->add(new Textarea("beschrijving2", array("id" => "beschrijving2","class" => "form-control")));
							
								$paginas = Pagina::find();
								$this->view->setVar("paginas", $paginas);
								
									
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("beschrijving1", array("string", "striptags"));	$validation->setFilters("beschrijving2", array("string", "striptags"));	$validation->setFilters("niveau", array("int", "striptags"));	$validation->setFilters("hoofdtitel", array("string", "striptags"));	$validation->setFilters("header", array("string", "striptags"));	$validation->setFilters("locatiex", array("int", "striptags"));	$validation->setFilters("locatiey", array("int", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('niveau', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld niveau is verplicht.'
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
			
				$categoryx = Category::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($categoryx->id))
				{ 		 
					$category = new Category();
					if(strlen($post['id']) < 5){ $category->id = $this->uuid(); }else{ $category->id = $post['id']; }
				}
				else
				{
					$category = $categoryx;
				}
				
				//save standard columns 
				foreach ($category as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$category->$key = $post[$key]; 
					}				
				}
				
				$category->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); $category->userid = $this->user['id'];  
						if(isset($post['files'])){ $category->fileid = $post['files'][0]; }  $category->lastedit = new Phalcon\Db\RawValue('now()');   
							if(!isset($categoryx->id)){ $category->creationdate = new Phalcon\Db\RawValue('now()');  } 
									
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $category->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

				


if(isset($post['relaties_beheerder']))
{

}

if(isset($post['relaties_leden']))
{

}




/*
email add
*/


if(isset($post['relaties_beheerder']))
{

}

if(isset($post['relaties_leden']))
{

}

	
				
				if($category->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($category->getMessages() as $message)
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
			$category = Category::findFirst('id = "'.$post['id'].'"');
			if($category->delete())
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

		$category = Category::findFirst('id = "'.$id.'"');
		$this->view->setVar("category", $category);
		$this->view->setVar("entityid", $category->id);
		
		$tabs = array('bericht','event','pagina','workshop');
		$this->view->setVar("tabs", $tabs);
		
		

if(!isset($allusers))
{
	$alluser = new Users();
	$this->views->setvar('relaties_allusers',$allusers);
}

if(!isset($form))
{
	$form = new Form();
}

$form->add(new Select('relaties_beheerder',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_beheerder','class' => 'form-control relaties-beheerder-multiple-select','data-placeholder' => 'Kies een beheerder')));
$form->add(new Select('relaties_leden',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_leden','class' => 'form-control relaties-leden-multiple-select','data-placeholder' => 'Kies hier uw leden.')));


	/*
		THIS IS THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/	
	
	$message = Message::findFirst('id = "72559491-9d9e-4733-b0c8-d3f064c42284"');
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
	

	/*
		THIS IS THE END OF THE THE EMAIL MODULE INCLUDED BY THE CONTROLLER GENERATOR
	*/

if(!isset($allusers))
{
	$alluser = new Users();
	$this->views->setvar('relaties_allusers',$allusers);
}

if(!isset($form))
{
	$form = new Form();
}

$form->add(new Select('relaties_beheerder',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_beheerder','class' => 'form-control relaties-beheerder-multiple-select','data-placeholder' => 'Kies een beheerder')));
$form->add(new Select('relaties_leden',User::find(), array('using' => array('id', 'email'),'id' => 'relaties_leden','class' => 'form-control relaties-leden-multiple-select','data-placeholder' => 'Kies hier uw leden.')));



		
		
					//start bericht stuff 
					
					$actions = $this->actionauth('bericht');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "bericht"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("berichtcolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Bericht';
					$args['args'] = 'categoryid = "'.$category->id.'"';
					$args['search'] = false;
					
					$indexberichts = $this->search2($args); 		
					$this->view->setVar("indexberichts", $indexberichts);
					
					//end bericht stuff
					
					//start event stuff 
					
					$actions = $this->actionauth('event');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "event"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("eventcolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Event';
					$args['args'] = 'categoryid = "'.$category->id.'"';
					$args['search'] = false;
					
					$indexevents = $this->search2($args); 		
					$this->view->setVar("indexevents", $indexevents);
					
					//end event stuff
					
					//start pagina stuff 
					
					$actions = $this->actionauth('pagina');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "pagina"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("paginacolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Pagina';
					$args['args'] = 'categoryid = "'.$category->id.'"';
					$args['search'] = false;
					
					$indexpaginas = $this->search2($args); 		
					$this->view->setVar("indexpaginas", $indexpaginas);
					
					//end pagina stuff
					
					//start workshop stuff 
					
					$actions = $this->actionauth('workshop');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "workshop"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("workshopcolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Workshop';
					$args['args'] = 'categoryid = "'.$category->id.'"';
					$args['search'] = false;
					
					$indexworkshops = $this->search2($args); 		
					$this->view->setVar("indexworkshops", $indexworkshops);
					
					//end workshop stuff
					
		
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
			$allcolumns = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');
			$table = Column::findFirst('entity = "category"');
			$columns = $table->columns;		

			$args['entity'] = 'category';
			$args['columns'] = array('titel','slug','beschrijving1','beschrijving2','niveau','hoofdtitel','header','lastedit','creationdate','locatiex','locatiey');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$categorys = $this->search2($args); 

			if(count($categorys) > 0)
			{ 				
				$status['goto'] = $this->csv($categorys,$columns,'category');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
