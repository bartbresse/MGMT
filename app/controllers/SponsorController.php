<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class SponsorController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Sponsors');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Sponsor');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "sponsor"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','url','beschrijving','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		
	
		$columns = array('titel','slug','url','beschrijving','creationdate','lastedit');		
		$sponsors = $this->search($columns,'Sponsor'); 
		if(count($sponsors) > 0){ $this->view->setVar("sponsors", $sponsors); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "sponsor"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }
		$this->view->setVar("sponsorcolumns", $columns);	
		
		$allcolumns = array('titel','slug','url','beschrijving','creationdate','lastedit');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','slug','url','beschrijving','creationdate','lastedit');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$sponsors = $this->search($columns,'Sponsor',$args); 
		if(count($sponsors) > 0){ $this->view->setVar("indexsponsors", $sponsors); }		
		$this->view->partial("sponsor/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "sponsor"');
			$allcolumns = array('titel','slug','url','beschrijving','creationdate','lastedit');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("lastedit"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("sponsorcolumns", $columns);	

			$args['entity'] = 'sponsor';
			$args['columns'] = array('titel','slug','url','beschrijving','creationdate','lastedit');
			
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
				$this->view->setVar("indexsponsors", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("sponsor/clean"); 	
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
				$sponsor = Sponsor::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$sponsor = Sponsor::findFirst();
			}	
		
			$cm = $sponsor->columnMap();
		
			$this->view->setVar("sponsor", $sponsor);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $sponsor->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$sponsor = Sponsor::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($sponsor as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($sponsor->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($sponsor->getMessages() as $message)
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

		$sponsor = Sponsor::findFirst('id = "'.$id.'"');
		$cm = $sponsor->columnMap();
		
		$this->view->setVar("sponsor", $sponsor);
		
		//files
		$files = $this->getfiles('sponsor',$sponsor->id);		
		$this->view->setVar("files", $files);	
	
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $sponsor->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://","value" => $sponsor->url)));
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
								
		$this->view->setVar("form", $form);
		$this->view->pick("sponsor/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$sponsor = Sponsor::findFirst();
		if(!isset($sponsor->id))
		{
			$sponsor = new Sponsor();
			$sponsor->userid = $session['id'];
		}
		
		$cm = $sponsor->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://")));	$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
									
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("url", array("string", "striptags"));	$validation->setFilters("beschrijving", array("string", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('url', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld url is verplicht.'
							)));
							
							$validation->add('url', new StringLength(array(
								'messageMinimum' => 'Vul een url in van tenminste 2 characters.',
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
				$sponsorx = Sponsor::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($sponsorx->id))
				{ 		 
					$sponsor = new Sponsor();
					if(strlen($post['id']) < 5){ $sponsor->id = $this->uuid(); }else{ $sponsor->id = $post['id']; }
					$new = true;
				}
				else
				{
					$sponsor = $sponsorx;
				}
				
				//save standard columns 
				foreach ($sponsor as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$sponsor->$key = $post[$key]; 
					}				
				}
				
				$sponsor->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));if(isset($post['files'])){ $sponsor->fileid = $post['files'][0]; }  $sponsor->userid = $this->user['id'];  
						if(!isset($sponsorx->id)){ $sponsor->creationdate = new Phalcon\Db\RawValue('now()');  } 
							 $sponsor->lastedit = new Phalcon\Db\RawValue('now()');   
									
				
				$cc=0;	
				if($true)
				{
					$messages = $sponsor->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($sponsor->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($sponsor->getMessages() as $message)
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
			$sponsor = Sponsor::findFirst('id = "'.$post['id'].'"');
			if($sponsor->delete())
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

		$sponsor = Sponsor::findFirst('id = "'.$id.'"');
		$this->view->setVar("sponsor", $sponsor);
		$this->view->setVar("entityid", $sponsor->id);
		
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
			$allcolumns = array('titel','slug','url','beschrijving','creationdate','lastedit');
			$table = Column::findFirst('entity = "sponsor"');
			$columns = $table->columns;		

			$args['entity'] = 'sponsor';
			$args['columns'] = array('titel','slug','url','beschrijving','creationdate','lastedit');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$sponsors = $this->search2($args); 

			if(count($sponsors) > 0)
			{ 				
				$status['goto'] = $this->csv($sponsors,$columns,'sponsor');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
