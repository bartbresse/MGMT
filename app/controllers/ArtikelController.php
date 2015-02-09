<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class ArtikelController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Artikelen');
        parent::initialize();
    }

    public function indexAction()
    {		
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Artikel');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "artikel"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("url"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','prijs','url');
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','prijs','url');		
		$artikels = $this->search($columns,'Artikel'); 
		if(count($artikels) > 0){ $this->view->setVar("artikels", $artikels); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "artikel"');
		if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("url"); }
		$this->view->setVar("artikelcolumns", $columns);	
		
		$allcolumns = array('titel','slug','prijs','url');
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','slug','prijs','url');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$artikels = $this->search($columns,'Artikel',$args); 
		if(count($artikels) > 0){ $this->view->setVar("indexartikels", $artikels); }		
		$this->view->partial("artikel/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$allcolumns = array('titel','slug','prijs','url');
			$table = Column::findFirst('entity = "artikel"');
			$columns = $table->columns;		

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("artikelcolumns", $columns);	

			$args['entity'] = 'artikel';
			$args['columns'] = array('titel','slug','prijs','url');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			
			$users = $this->search2($args); 
			if(count($users) > 0)
			{ 
				$this->view->setVar("indexartikels", $users); 
			}

			//sort post. used to remember the sorted column
			$sort = explode(' ',$post['order']);
			$post['key'] = $sort[0];
			$post['order'] = $sort[1];
			$this->view->setvar("post", $post);
			$this->view->partial("artikel/clean"); 	
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
				$artikel = Artikel::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$artikel = Artikel::findFirst();
			}	
		
			$cm = $artikel->columnMap();
		
			$this->view->setVar("artikel", $artikel);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $artikel->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$artikel = Artikel::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($artikel as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($artikel->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($artikel->getMessages() as $message)
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

		$artikel = Artikel::findFirst('id = "'.$id.'"');
		$cm = $artikel->columnMap();
		
		$this->view->setVar("artikel", $artikel);
		
		
			
					//files
					$files = $this->getfiles('artikel',$artikel->id);		
					$this->view->setVar("files", $files);	
			
					
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $artikel->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://","value" => $artikel->url)));		

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("artikel/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$artikel = Artikel::findFirst();
		if(!isset($artikel->id))
		{
			$artikel = new Artikel();
			$artikel->userid = $session['id'];
		}
		
		$cm = $artikel->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://")));			
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("prijs", array("string", "striptags"));	$validation->setFilters("url", array("string", "striptags"));	 

			
							$validation->add('titel', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld titel is verplicht.'
							)));
							
							$validation->add('titel', new StringLength(array(
								'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
								'min' => 2
							)));
							
							$validation->add('prijs', new PresenceOf(array(
								'field' => 'specificaties',
								'message' => 'Het veld prijs is verplicht.'
							)));
							
							$validation->add('prijs', new StringLength(array(
								'messageMinimum' => 'Vul een prijs in van tenminste 2 characters.',
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
				$artikelx = Artikel::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($artikelx->id))
				{ 		 
					$artikel = new Artikel();
					if(strlen($post['id']) < 5){ $artikel->id = $this->uuid(); }else{ $artikel->id = $post['id']; }
					$new = true;
				}
				else
				{
					$artikel = $artikelx;
				}
				
				//save standard columns 
				foreach ($artikel as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$artikel->$key = $post[$key]; 
					}				
				}
				
				$artikel->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));if(isset($post['files'])){ $artikel->fileid = $post['files'][0]; } 		
				
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $artikel->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($artikel->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($artikel->getMessages() as $message)
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
			$artikel = Artikel::findFirst('id = "'.$post['id'].'"');
			if($artikel->delete())
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

		$artikel = Artikel::findFirst('id = "'.$id.'"');
		$this->view->setVar("artikel", $artikel);
		$this->view->setVar("entityid", $artikel->id);
		
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
			$allcolumns = array('titel','slug','prijs','url');
			$table = Column::findFirst('entity = "artikel"');
			$columns = $table->columns;		

			$args['entity'] = 'artikel';
			$args['columns'] = array('titel','slug','prijs','url');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$artikels = $this->search2($args); 

			if(count($artikels) > 0)
			{ 				
				$status['goto'] = $this->csv($artikels,$columns,'artikel');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
