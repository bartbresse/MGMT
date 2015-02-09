<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class BerichtreactieController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Berichtreacties');
        parent::initialize();
    }

    public function indexAction()
    {			
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Berichtreactie');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "berichtreactie"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("nummer"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('bericht','creationdate','lastedit','nummer');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('bericht','creationdate','lastedit','nummer');		
		$berichtreacties = $this->search($columns,'Berichtreactie'); 
		if(count($berichtreacties) > 0){ $this->view->setVar("berichtreacties", $berichtreacties); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "berichtreactie"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("nummer"); }
		$this->view->setVar("berichtreactiecolumns", $columns);	
		
		$allcolumns = array('bericht','creationdate','lastedit','nummer');

		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('bericht','creationdate','lastedit','nummer');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$berichtreacties = $this->search($columns,'Berichtreactie',$args); 
		if(count($berichtreacties) > 0){ $this->view->setVar("indexberichtreacties", $berichtreacties); }		
		$this->view->partial("berichtreactie/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "berichtreactie"');
			
			$allcolumns = array('bericht','creationdate','lastedit','nummer');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("nummer"); }	

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("berichtreactiecolumns", $columns);	

			$args['entity'] = 'berichtreactie';
			$args['columns'] = array('bericht','creationdate','lastedit','nummer');
			
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
				$this->view->setVar("indexberichtreacties", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("berichtreactie/clean"); 	
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
				$berichtreactie = Berichtreactie::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$berichtreactie = Berichtreactie::findFirst();
			}	
		
			$cm = $berichtreactie->columnMap();
		
			$this->view->setVar("berichtreactie", $berichtreactie);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $berichtreactie->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$berichtreactie = Berichtreactie::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($berichtreactie as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($berichtreactie->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($berichtreactie->getMessages() as $message)
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

		$berichtreactie = Berichtreactie::findFirst('id = "'.$id.'"');
		$cm = $berichtreactie->columnMap();
		
		$this->view->setVar("berichtreactie", $berichtreactie);
		
		
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $berichtreactie->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		//special field line controller.php line:95
		if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
		if($sessionuser['clearance'] < 900)
		{
			$q = $this->orquery('',$this->check('bericht'));
			$resultset = Bericht::find(array('conditions' => $q));
		}
		else
		{
			$resultset = Bericht::find();
		}
		
		$this->view->setVar("berichts",$resultset);	
		$form->add(new Textarea("bericht", array("id" => "bericht","class" => "form-control")));
		$form->add(new Select("parentid",Bericht::find(),array('using' => array('id', 'titel'),"id" => "parentid","class" => "form-control"))); 
		
		$this->view->setVar("form", $form);
		$this->view->pick("berichtreactie/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$berichtreactie = Berichtreactie::findFirst();
		if(!isset($berichtreactie->id))
		{
			$berichtreactie = new Berichtreactie();
			$berichtreactie->userid = $session['id'];
		}
		
		$cm = $berichtreactie->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('',$this->check('bericht'));
									$resultset = Bericht::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Bericht::find();
								}
								$this->view->setVar("berichts",$resultset);	
							$form->add(new Textarea("bericht", array("id" => "bericht","class" => "form-control")));
							
							$form->add(new Select("parentid",Berichtreactie::find(),array('using' => array('id', 'titel'),"id" => "parentid","class" => "form-control")));
									
		
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
	
			$validation->setFilters("bericht", array("string", "striptags"));	$validation->setFilters("nummer", array("int", "striptags"));	 

			$validation->add('bericht', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld bericht is verplicht.'
			)));
			
			$validation->add('bericht', new StringLength(array(
				'messageMinimum' => 'Vul een bericht in van tenminste 2 characters.',
				'min' => 2
			)));
			
			$validation->add('nummer', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld nummer is verplicht.'
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
				$berichtreactiex = Berichtreactie::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($berichtreactiex->id))
				{ 		 
					$berichtreactie = new Berichtreactie();
					if(strlen($post['id']) < 5){ $berichtreactie->id = $this->uuid(); }else{ $berichtreactie->id = $post['id']; }
					$new = true;
				}
				else
				{
					$berichtreactie = $berichtreactiex;
				}
				
				//save standard columns 
				foreach ($berichtreactie as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$berichtreactie->$key = $post[$key]; 
					}				
				}
				
				 $berichtreactie->userid = $this->user['id'];  
						if(!isset($berichtreactiex->id)){ $berichtreactie->creationdate = new Phalcon\Db\RawValue('now()');  } 
							 $berichtreactie->lastedit = new Phalcon\Db\RawValue('now()');   
									
				
				$cc=0;	
				if($new)
				{
					$messages = $berichtreactie->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($cc==0 && $berichtreactie->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($berichtreactie->getMessages() as $message)
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
			$berichtreactie = Berichtreactie::findFirst('id = "'.$post['id'].'"');
			if($berichtreactie->delete())
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

		$berichtreactie = Berichtreactie::findFirst('id = "'.$id.'"');
		$this->view->setVar("berichtreactie", $berichtreactie);
		$this->view->setVar("entityid", $berichtreactie->id);
		
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
			$allcolumns = array('bericht','creationdate','lastedit','nummer');
			$table = Column::findFirst('entity = "berichtreactie"');
			$columns = $table->columns;		

			$args['entity'] = 'berichtreactie';
			$args['columns'] = array('bericht','creationdate','lastedit','nummer');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$berichtreacties = $this->search2($args); 

			if(count($berichtreacties) > 0)
			{ 				
				$status['goto'] = $this->csv($berichtreacties,$columns,'berichtreactie');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
