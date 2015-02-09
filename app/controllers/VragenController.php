<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class VragenController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Vragens');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$actions = $this->actionauth('Vragen');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "vragen"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('vraag','antwoord','creationdate','lastedit');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('vraag','antwoord','creationdate','lastedit');		
		$vragens = $this->search($columns,'Vragen'); 
		if(count($vragens) > 0){ $this->view->setVar("vragens", $vragens); }
	}

	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('vraag','antwoord','creationdate','lastedit');
			$table = Column::findFirst('entity = "vragen"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("columns", $columns);	

			$args['entity'] = 'vragen';
			$args['columns'] = array('vraag','antwoord','creationdate','lastedit');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("vragens", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);

			$this->view->partial("vragen/index"); 	
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
				$vragen = Vragen::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$vragen = Vragen::findFirst();
			}	
		
			$cm = $vragen->columnMap();
		
			$this->view->setVar("vragen", $vragen);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $vragen->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$vragen = Vragen::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($vragen as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($vragen->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($vragen->getMessages() as $message)
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

		$vragen = Vragen::findFirst('id = "'.$id.'"');
		$cm = $vragen->columnMap();
		
		$this->view->setVar("vragen", $vragen);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $vragen->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("vraag", array("id" => "vraag","class" => "form-control")));
							$form->add(new Textarea("antwoord", array("id" => "antwoord","class" => "form-control")));
								

		$this->view->setVar("form", $form);
		$this->view->pick("vragen/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$vragen = Vragen::findFirst();
		if(!isset($vragen->id))
		{
			$vragen = new Vragen();
			$vragen->userid = $session['id'];
		}
		
		$cm = $vragen->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Textarea("vraag", array("id" => "vraag","class" => "form-control")));
							$form->add(new Textarea("antwoord", array("id" => "antwoord","class" => "form-control")));
									

		$this->view->setVar("form", $form);
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		
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
			
				$vragenx = Vragen::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($vragenx->id))
				{ 		 
					$vragen = new Vragen();
					if(strlen($post['id']) < 5){ $vragen->id = $this->uuid(); }else{ $vragen->id = $post['id']; }
				}
				else
				{
					$vragen = $vragenx;
				}
				
				//save standard columns 
				foreach ($vragen as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$vragen->$key = $post[$key]; 
					}				
				}
				
				if(isset($post['id']) && strlen($post['id']) == 0){ $vragen->userid = $this->user['id'];  }   
					if(!isset($vragenx->id)){ $vragen->creationdate = new Phalcon\Db\RawValue('now()');  } 
						 $vragen->lastedit = new Phalcon\Db\RawValue('now()');   
								
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $vragen->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

				if($vragen->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($vragen->getMessages() as $message)
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
			$vragen = Vragen::findFirst('id = "'.$post['id'].'"');
			if($vragen->delete())
			{
				$status['status'] = 'ok';
			}
		}
		echo json_encode($status);
	}

	public function viewAction()
	{
		$id = $this->request->getQuery('id');

		$vragen = Vragen::findFirst('id = "'.$id.'"');
		$this->view->setVar("vragen", $vragen);

		$tabs = array();

		$this->view->setVar("tabs", $tabs);
		
		
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
			$table = Column::findFirst('entity = "vragen"');
			$columns = $table->columns;		

			$args['entity'] = 'vragen';
			$args['columns'] = array('vraag','antwoord','creationdate','lastedit');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$vragens = $this->search2($args); 

			if(count($vragens) > 0)
			{ 				
				$status['goto'] = $this->csv($vragens,$columns,'vragen');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
