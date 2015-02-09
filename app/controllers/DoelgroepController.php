<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class DoelgroepController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Doelgroepen');
        parent::initialize();
    }

    public function indexAction()
    {		
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Doelgroep');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "doelgroep"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('beschrijving','titel','slug','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	

		$columns = array('beschrijving','titel','slug','creationdate','lastedit');		
		$doelgroeps = $this->search($columns,'Doelgroep'); 
		if(count($doelgroeps) > 0){ $this->view->setVar("doelgroeps", $doelgroeps); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "doelgroep"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("doelgroepcolumns", $columns);	
		
		$allcolumns = array('beschrijving','titel','slug','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('beschrijving','titel','slug','creationdate','lastedit');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$doelgroeps = $this->search($columns,'Doelgroep',$args); 
		if(count($doelgroeps) > 0){ $this->view->setVar("indexdoelgroeps", $doelgroeps); }		
		$this->view->partial("doelgroep/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "doelgroep"');
			$allcolumns = array('beschrijving','titel','slug','creationdate','lastedit');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }	

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("doelgroepcolumns", $columns);	

			$args['entity'] = 'doelgroep';
			$args['columns'] = array('beschrijving','titel','slug','creationdate','lastedit');
			
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
				$this->view->setVar("indexdoelgroeps", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("doelgroep/clean"); 	
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
				$doelgroep = Doelgroep::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$doelgroep = Doelgroep::findFirst();
			}	
		
			$cm = $doelgroep->columnMap();
		
			$this->view->setVar("doelgroep", $doelgroep);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $doelgroep->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$doelgroep = Doelgroep::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($doelgroep as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($doelgroep->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($doelgroep->getMessages() as $message)
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

		$doelgroep = Doelgroep::findFirst('id = "'.$id.'"');
		$cm = $doelgroep->columnMap();
		
		$this->view->setVar("doelgroep", $doelgroep);
		
		
			
					//files
					$files = $this->getfiles('doelgroep',$doelgroep->id);		
					$this->view->setVar("files", $files);	
			
					
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $doelgroep->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
		
		$this->view->setVar("form", $form);
		$this->view->pick("doelgroep/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$doelgroep = Doelgroep::findFirst();
		if(!isset($doelgroep->id))
		{
			$doelgroep = new Doelgroep();
			$doelgroep->userid = $session['id'];
		}
		
		$cm = $doelgroep->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
									
		
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
	
			$validation->setFilters("beschrijving", array("string", "striptags"));	$validation->setFilters("titel", array("string", "striptags"));	 

			
							$validation->add('beschrijving', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld beschrijving is verplicht.'
							)));
							
							$validation->add('beschrijving', new StringLength(array(
								'messageMinimum' => 'Vul een beschrijving in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
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
				$doelgroepx = Doelgroep::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($doelgroepx->id))
				{ 		 
					$doelgroep = new Doelgroep();
					if(strlen($post['id']) < 5){ $doelgroep->id = $this->uuid(); }else{ $doelgroep->id = $post['id']; }
					$new = true;
				}
				else
				{
					$doelgroep = $doelgroepx;
				}				
				
				//save standard columns 
				foreach ($doelgroep as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && $key != 'creationdate')
					{ 
						$doelgroep->$key = $post[$key]; 
					}				
				}
				
				$doelgroep->userid = $this->user['id'];  
				if(isset($post['files'])){ $doelgroep->fileid = $post['files'][0]; } 
				$doelgroep->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
				if($new){ $doelgroep->creationdate = new Phalcon\Db\RawValue('now()'); } 
				else { $doelgroep->creationdate = date('Y-m-d H:i:s',strtotime($doelgroep->creationdate)); }
				$doelgroep->lastedit = new Phalcon\Db\RawValue('now()');   
				
				$cc=0;	
				if($new)
				{
					$messages = $doelgroep->validation();
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

				/* CONNECT A TAG TO THIS ENTITY END */				
				
				if($cc==0 && $doelgroep->save() && $tag->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($doelgroep->getMessages() as $message)
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
			$doelgroep = Doelgroep::findFirst('id = "'.$post['id'].'"');
			$tag = Tag::findFirst('entityid = "'.$post['id'].'"');
			if($doelgroep->delete() && $tag->delete())
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

		$doelgroep = Doelgroep::findFirst('id = "'.$id.'"');
		$this->view->setVar("doelgroep", $doelgroep);
		$this->view->setVar("entityid", $doelgroep->id);
		
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
			$allcolumns = array('beschrijving','titel','slug','creationdate','lastedit');
			$table = Column::findFirst('entity = "doelgroep"');
			$columns = $table->columns;		

			$args['entity'] = 'doelgroep';
			$args['columns'] = array('beschrijving','titel','slug','creationdate','lastedit');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$doelgroeps = $this->search2($args); 

			if(count($doelgroeps) > 0)
			{ 				
				$status['goto'] = $this->csv($doelgroeps,$columns,'doelgroep');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
