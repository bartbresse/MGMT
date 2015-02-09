<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class WorkshopController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Workshops');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		$actions = $this->actionauth('Workshop');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "workshop"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','beschrijving');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','beschrijving');		
		$workshops = $this->search($columns,'Workshop'); 
		if(count($workshops) > 0){ $this->view->setVar("workshops", $workshops); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "workshop"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }
		$this->view->setVar("workshopcolumns", $columns);	
		
		$allcolumns = array('titel','slug','beschrijving');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','slug','beschrijving');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$workshops = $this->search($columns,'Workshop',$args); 
		if(count($workshops) > 0){ $this->view->setVar("indexworkshops", $workshops); }		
		$this->view->partial("workshop/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "workshop"');
			$allcolumns = array('titel','slug','beschrijving');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("workshopcolumns", $columns);	

			$args['entity'] = 'workshop';
			$args['columns'] = array('titel','slug','beschrijving');
			
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
				$this->view->setVar("indexworkshops", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("workshop/clean"); 	
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
				$workshop = Workshop::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$workshop = Workshop::findFirst();
			}	
		
			$cm = $workshop->columnMap();
		
			$this->view->setVar("workshop", $workshop);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $workshop->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$workshop = Workshop::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($workshop as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($workshop->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($workshop->getMessages() as $message)
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

		$workshop = Workshop::findFirst('id = "'.$id.'"');
		$cm = $workshop->columnMap();
		
		$this->view->setVar("workshop", $workshop);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $workshop->$column,'id' => $column,'class' => 'form-control'))); }	
		
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
								

		
		


		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("workshop/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$workshop = Workshop::findFirst();
		if(!isset($workshop->id))
		{
			$workshop = new Workshop();
			$workshop->userid = $session['id'];
		}
		
		$cm = $workshop->columnMap();
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
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("beschrijving", array("string", "striptags"));	 
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
				$workshopx = Workshop::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($workshopx->id))
				{ 		 
					$workshop = new Workshop();
					if(strlen($post['id']) < 5){ $workshop->id = $this->uuid(); }else{ $workshop->id = $post['id']; }
					$new = true;
				}
				else
				{
					$workshop = $workshopx;
				}
				
				//save standard columns 
				foreach ($workshop as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$workshop->$key = $post[$key]; 
					}				
				}
				
				$workshop->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); $workshop->userid = $this->user['id'];  
								
				
				$cc=0;	
				if($new)
				{
					$messages = $workshop->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
				
				if($workshop->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($workshop->getMessages() as $message)
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
			$workshop = Workshop::findFirst('id = "'.$post['id'].'"');
			if($workshop->delete())
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

		$workshop = Workshop::findFirst('id = "'.$id.'"');
		$this->view->setVar("workshop", $workshop);
		$this->view->setVar("entityid", $workshop->id);
		
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
			$allcolumns = array('titel','slug','beschrijving');
			$table = Column::findFirst('entity = "workshop"');
			$columns = $table->columns;		

			$args['entity'] = 'workshop';
			$args['columns'] = array('titel','slug','beschrijving');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$workshops = $this->search2($args); 

			if(count($workshops) > 0)
			{ 				
				$status['goto'] = $this->csv($workshops,$columns,'workshop');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
