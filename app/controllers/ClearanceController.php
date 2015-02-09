<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class ClearanceController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Clearances');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Clearance');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "clearance"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("value"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','value');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('titel','value');		
		$clearances = $this->search($columns,'Clearance',$args); 
		if(count($clearances) > 0){ $this->view->setVar("clearances", $clearances); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "clearance"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("value"); }
		$this->view->setVar("clearancecolumns", $columns);	
		
		$allcolumns = array('titel','value');
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','value');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$clearances = $this->search($columns,'Clearance',$args); 
		if(count($clearances) > 0){ $this->view->setVar("indexclearances", $clearances); }		
		$this->view->partial("clearance/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('titel','value');
			$table = Column::findFirst('entity = "clearance"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("clearancecolumns", $columns);	

			$args['entity'] = 'clearance';
			$args['columns'] = array('titel','value');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indexclearances", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("clearance/clean"); 	
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
				$clearance = Clearance::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$clearance = Clearance::findFirst();
			}	
		
			$cm = $clearance->columnMap();
		
			$this->view->setVar("clearance", $clearance);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $clearance->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$clearance = Clearance::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($clearance as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($clearance->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($clearance->getMessages() as $message)
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

		$clearance = Clearance::findFirst('id = "'.$id.'"');
		$cm = $clearance->columnMap();
		
		$this->view->setVar("clearance", $clearance);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $clearance->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("clearance/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$clearance = Clearance::findFirst();
		if(!isset($clearance->id))
		{
			$clearance = new Clearance();
			$clearance->userid = $session['id'];
		}
		
		$cm = $clearance->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("value", array("int", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('value', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld value is verplicht.'
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
				$clearancex = Clearance::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($clearancex->id))
				{ 		 
					$clearance = new Clearance();
					if(strlen($post['id']) < 5){ $clearance->id = $this->uuid(); }else{ $clearance->id = $post['id']; }
					$new = true;
				}
				else
				{
					$clearance = $clearancex;
				}
				
				//save standard columns 
				foreach ($clearance as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$clearance->$key = $post[$key]; 
					}				
				}
				
						
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $clearance->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($clearance->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($clearance->getMessages() as $message)
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
			$clearance = Clearance::findFirst('id = "'.$post['id'].'"');
			if($clearance->delete())
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

		$clearance = Clearance::findFirst('id = "'.$id.'"');
		$this->view->setVar("clearance", $clearance);
		$this->view->setVar("entityid", $clearance->id);
		
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
			$allcolumns = array('titel','value');
			$table = Column::findFirst('entity = "clearance"');
			$columns = $table->columns;		

			$args['entity'] = 'clearance';
			$args['columns'] = array('titel','value');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$clearances = $this->search2($args); 

			if(count($clearances) > 0)
			{ 				
				$status['goto'] = $this->csv($clearances,$columns,'clearance');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
