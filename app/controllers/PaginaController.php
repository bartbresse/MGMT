<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class PaginaController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Paginas');
        parent::initialize();
    }

    public function indexAction()
    {		
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Pagina');				

		$table = Column::findFirst('entity = "pagina"');
		$allcolumns = array('titel','slug','beschrijving','creationdate','lastedit','url','categorie','niveau');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("niveau"); }
		
		$this->view->setVar("actions", $actions);
		$this->view->setVar("allcolumns", $allcolumns);	
		$this->view->setVar("columns", $columns);	
		
		$columns = array('titel','slug','beschrijving','creationdate','lastedit','url','categorie','niveau');		
		$paginas = $this->search($columns,'Pagina'); 
		if(count($paginas) > 0){ $this->view->setVar("paginas", $paginas); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "pagina"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("niveau"); }
		$this->view->setVar("paginacolumns", $columns);	
		
		$allcolumns = array('titel','slug','beschrijving','creationdate','lastedit','url','categorie','niveau');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$paginas = $this->search($columns,'Pagina',$args); 
		if(count($paginas) > 0){ $this->view->setVar("indexpaginas", $paginas); }		
		$this->view->partial("pagina/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "pagina"');
			$allcolumns = array('titel','slug','beschrijving','creationdate','lastedit','url','niveau');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("niveau"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("paginacolumns", $columns);	

			$args['entity'] = 'pagina';
			$args['columns'] = array('titel','slug','beschrijving','creationdate','lastedit','url','niveau');
			
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
				$this->view->setVar("indexpaginas", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("pagina/clean"); 	
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
				$pagina = Pagina::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$pagina = Pagina::findFirst();
			}	
		
			$cm = $pagina->columnMap();
		
			$this->view->setVar("pagina", $pagina);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $pagina->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$pagina = Pagina::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($pagina as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($pagina->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($pagina->getMessages() as $message)
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

		$pagina = Pagina::findFirst('id = "'.$id.'"');
		$cm = $pagina->columnMap();
		
		$this->view->setVar("pagina", $pagina);
		
		
			
					//files
					$files = $this->getfiles('pagina',$pagina->id);		
					$this->view->setVar("files", $files);	
			
					
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $pagina->$column,'id' => $column,'class' => 'form-control'))); }	
		
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
		
		$hoofdmenupages = Pagina::find('niveau = 0');
		$submenupaginas = Pagina::find('niveau = 1');

		$this->view->setVar("categorys",$resultset);	
		$this->view->setVar("hoofdmenupages",$hoofdmenupages);	
		$this->view->setVar("submenupaginas",$submenupaginas);	
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://","value" => $pagina->url)));	
		$form->add(new Select("parentid",Pagina::find(),array('using' => array('id', 'titel'),"id" => "parentid","class" => "form-control")));		
		
		$this->view->setVar("form", $form);
		$this->view->pick("pagina/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$pagina = Pagina::findFirst();
		if(!isset($pagina->id))
		{
			$pagina = new Pagina();
			$pagina->userid = $session['id'];
		}
		
		$cm = $pagina->columnMap();
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
		
		$hoofdmenupages = Pagina::find('niveau = 0');
		$submenupaginas = Pagina::find('niveau = 1');

		$this->view->setVar("categorys",$resultset);	
		$this->view->setVar("hoofdmenupages",$hoofdmenupages);	
		$this->view->setVar("submenupaginas",$submenupaginas);
		
		$form->add(new Text("url", array("id" => "url","class" => "form-control","placeholder" => "http://")));	
		$form->add(new Select("parentid",Pagina::find(),array('using' => array('id', 'titel'),"id" => "parentid","class" => "form-control")));
									
		
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
	
			$validation->setFilters("titel", array("string", "striptags"));	$validation->setFilters("beschrijving", array("string", "striptags"));	$validation->setFilters("url", array("string", "striptags"));	$validation->setFilters("categoryid", array("string", "striptags"));	$validation->setFilters("parentid", array("string", "striptags"));	$validation->setFilters("niveau", array("int", "striptags"));	 

			
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
				$paginax = Pagina::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($paginax->id))
				{ 		 
					$pagina = new Pagina();
					if(strlen($post['id']) < 5){ $pagina->id = $this->uuid(); }else{ $pagina->id = $post['id']; }
					$new = true;
				}
				else
				{
					$pagina = $paginax;
				}
				
				//save standard columns 
				foreach ($pagina as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$pagina->$key = $post[$key]; 
					}				
				}
				
				//Reset child pages
				$children = Pagina::find('categoryid = "'.$pagina->id.'"');
				foreach($children as $child) {
					$child->niveau = $pagina->niveau + 1;
					$child->save();
				}
				
				if($pagina->niveau == 0) {
					$pagina->categoryid = null;					
				}
				
				if($pagina->niveau == 2) {
					$pagina->categoryid = $post['categoryid2'];
					//Reset child pages
					$children = Pagina::find('categoryid = "'.$pagina->id.'"');
					foreach($children as $child) {
						$child->category = null;
						$child->niveau = 0;
						$child->save();
					}
				}
				
				$pagina->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); 
				$pagina->userid = $this->user['id'];
				
				if(isset($post['files']))
				{
					$pagina->fileid = $post['files'][0]; 				
				}
				
				if(!isset($paginax->id))
				{ 
					$pagina->creationdate = new Phalcon\Db\RawValue('now()');  
				}
				
				$pagina->lastedit = new Phalcon\Db\RawValue('now()');   
				
				$cc=0;	
				if($new)
				{
					$messages = $pagina->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	

					
				
				if($cc==0 && $pagina->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($pagina->getMessages() as $message)
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
			$pagina = Pagina::findFirst('id = "'.$post['id'].'"');
			if($pagina->delete())
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

		$pagina = Pagina::findFirst('id = "'.$id.'"');
		$this->view->setVar("pagina", $pagina);
		$this->view->setVar("entityid", $pagina->id);
		
		$tabs = array('category');
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					//start category stuff 
					
					$actions = $this->actionauth('category');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "category"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("categorycolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Category';
					$args['args'] = 'paginaid = "'.$pagina->id.'"';
					$args['search'] = false;
					
					$indexcategorys = $this->search2($args); 		
					$this->view->setVar("indexcategorys", $indexcategorys);
					
					//end category stuff
					
		
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
			$allcolumns = array('titel','slug','beschrijving','creationdate','lastedit','url','categorie','niveau');
			$table = Column::findFirst('entity = "pagina"');
			$columns = $table->columns;		

			$args['entity'] = 'pagina';
			$args['columns'] = array('titel','slug','beschrijving','creationdate','lastedit','url','categorie','niveau');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$paginas = $this->search2($args); 

			if(count($paginas) > 0)
			{ 				
				$status['goto'] = $this->csv($paginas,$columns,'pagina');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
