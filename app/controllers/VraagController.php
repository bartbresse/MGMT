<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class VraagController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Vraags');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Vraag');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "vraag"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('vraag','antwoord','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('vraag','antwoord','creationdate','lastedit');		
		$vraags = $this->search($columns,'Vraag'); 
		if(count($vraags) > 0){ $this->view->setVar("vraags", $vraags); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "vraag"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("vraagcolumns", $columns);	
		
		$allcolumns = array('vraag','antwoord','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('vraag','antwoord','creationdate','lastedit');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$vraags = $this->search($columns,'Vraag',$args); 
		if(count($vraags) > 0){ $this->view->setVar("indexvraags", $vraags); }		
		$this->view->partial("vraag/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "vraag"');
			$allcolumns = array('vraag','antwoord','creationdate','lastedit');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("vraagcolumns", $columns);	

			$args['entity'] = 'vraag';
			$args['columns'] = array('vraag','antwoord','creationdate','lastedit');
			
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
				$this->view->setVar("indexvraags", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("vraag/clean"); 	
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
				$vraag = Vraag::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$vraag = Vraag::findFirst();
			}	
		
			$cm = $vraag->columnMap();
		
			$this->view->setVar("vraag", $vraag);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $vraag->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$vraag = Vraag::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($vraag as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($vraag->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($vraag->getMessages() as $message)
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

		$vraag = Vraag::findFirst('id = "'.$id.'"');
		$cm = $vraag->columnMap();
		
		$this->view->setVar("vraag", $vraag);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $vraag->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("vraag", array("id" => "vraag","class" => "form-control")));
							$form->add(new Textarea("antwoord", array("id" => "antwoord","class" => "form-control")));
								

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("vraag/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$vraag = Vraag::findFirst();
		if(!isset($vraag->id))
		{
			$vraag = new Vraag();
			$vraag->userid = $session['id'];
		}
		
		$cm = $vraag->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("vraag", array("id" => "vraag","class" => "form-control")));
							$form->add(new Textarea("antwoord", array("id" => "antwoord","class" => "form-control")));
									
		
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
	
			$validation->setFilters("vraag", array("string", "striptags"));	$validation->setFilters("antwoord", array("string", "striptags"));	 

			
							$validation->add('vraag', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld vraag is verplicht.'
							)));
							
							$validation->add('vraag', new StringLength(array(
								'messageMinimum' => 'Vul een vraag in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('antwoord', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld antwoord is verplicht.'
							)));
							
							$validation->add('antwoord', new StringLength(array(
								'messageMinimum' => 'Vul een antwoord in van tenminste 2 characters.',
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
				$vraagx = Vraag::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($vraagx->id))
				{ 		 
					$vraag = new Vraag();
					if(strlen($post['id']) < 5){ $vraag->id = $this->uuid(); }else{ $vraag->id = $post['id']; }
					$new = true;
				}
				else
				{
					$vraag = $vraagx;
				}
				
				//save standard columns 
				foreach ($vraag as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$vraag->$key = $post[$key]; 
					}				
				}
				
				 $vraag->userid = $this->user['id'];  
						if(!isset($vraagx->id)){ $vraag->creationdate = new Phalcon\Db\RawValue('now()');  } 
							 $vraag->lastedit = new Phalcon\Db\RawValue('now()');   
									
				
				$cc=0;	
				if($new)
				{
					$messages = $vraag->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($cc==0 && $vraag->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($vraag->getMessages() as $message)
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
			$vraag = Vraag::findFirst('id = "'.$post['id'].'"');
			if($vraag->delete())
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

		$vraag = Vraag::findFirst('id = "'.$id.'"');
		$this->view->setVar("vraag", $vraag);
		$this->view->setVar("entityid", $vraag->id);
		
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
			$allcolumns = array('vraag','antwoord','creationdate','lastedit');
			$table = Column::findFirst('entity = "vraag"');
			$columns = $table->columns;		

			$args['entity'] = 'vraag';
			$args['columns'] = array('vraag','antwoord','creationdate','lastedit');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$vraags = $this->search2($args); 

			if(count($vraags) > 0)
			{ 				
				$status['goto'] = $this->csv($vraags,$columns,'vraag');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
