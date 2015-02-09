<?php

use Phalcon\Forms\Form,
	Phalcon\Forms\FileUpload,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;
	
class BerichtController extends ControllerBase
{
    public function initialize()
    {
		$fileupload = new FileUpload('name','options');
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Berichten');
        parent::initialize();
    }

    public function indexAction()
    {		
		$get = $this->request->getQuery();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "bericht"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','creationdate','lastedit','beschrijving');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	

		$columns = array('titel','slug','creationdate','lastedit','beschrijving');		
		$berichts = $this->search($columns,'Bericht'); 
		if(count($berichts) > 0){ $this->view->setVar("berichts", $berichts); }
	}
	
	public function cleanAction()
	{
		
		$this->view->disable();
		$post = $this->request->getPost();

		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "bericht"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }
		$this->view->setVar("berichtcolumns", $columns);	
		
		$allcolumns = array('titel','slug','creationdate','lastedit','beschrijving');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		else
		{
			$args = '';
		}
		
		$berichts = $this->search($columns,'Bericht',$args); 
		if(count($berichts) > 0){ $this->view->setVar("indexberichts", $berichts); }		
		$this->view->partial("bericht/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "bericht"');
			
			$allcolumns = array('titel','slug','creationdate','lastedit','beschrijving');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("berichtcolumns", $columns);	

			$args['entity'] = 'bericht';
			$args['columns'] = array('titel','slug','creationdate','lastedit','beschrijving');
			
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
				$this->view->setVar("indexberichts", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("bericht/clean"); 	
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
				$bericht = Bericht::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$bericht = Bericht::findFirst();
			}	
		
			$cm = $bericht->columnMap();
		
			$this->view->setVar("bericht", $bericht);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $bericht->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$bericht = Bericht::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($bericht as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($bericht->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($bericht->getMessages() as $message)
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

		$bericht = Bericht::findFirst('id = "'.$id.'"');
		$cm = $bericht->columnMap();
		
		$this->view->setVar("bericht", $bericht);
		
		//files
		$files = $this->getfiles('bericht',$bericht->id);		
		$this->view->setVar("files", $files);	

		//relation
		$tags = Tag::find();
		$this->view->setVar("tags", $tags);

		//relations
		$berichttags = BerichtTag::find('berichtid = "'.$bericht->id.'"');
		$rtags = array();

		foreach($berichttags as $berichttag)
		{ array_push($rtags,$berichttag->tagid);	}
		$this->view->setVar("berichttags",$rtags);

		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $bericht->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
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
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
								
		$this->view->setVar("form", $form);
		$this->view->pick("bericht/new");	
	}

	public function newAction()
	{
		$form = new Form();
	//	$MGMTform = new MGMTForm();
		
		$session = $this->session->get('auth');

		$bericht = Bericht::findFirst();
		if(!isset($bericht->id))
		{
			$bericht = new Bericht();
			$bericht->userid = $session['id'];
		}
		
		$cm = $bericht->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
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
		$form->add(new Textarea("beschrijving", array("id" => "beschrijving","class" => "form-control")));
									
		//$fu = new Phalcon\Forms\FileUpload('name');

		
		$form->add(new FileUpload('file',array('stuff' => 'stuff')));
		
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
		//special field line controller.php line:95
		if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
		if($sessionuser['clearance'] < 900)
		{
			$q = $this->orquery('entityid',$this->check('tag'));
			$resultset = Tag::find(array('conditions' => $q));
		}
		else
		{
			$resultset = Tag::find();
		}

		$this->view->setVar("tags",$resultset);	
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
				$berichtx = Bericht::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($berichtx->id))
				{ 		 
					$bericht = new Bericht();
					if(strlen($post['id']) < 5){ $bericht->id = $this->uuid(); }else{ $bericht->id = $post['id']; }
					$new = true;
				}
				else
				{
					$bericht = $berichtx;
				}
				
				//save standard columns 
				foreach ($bericht as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && $key != 'creationdate')
					{ 
						$bericht->$key = $post[$key]; 
					}				
				}
				
				$bericht->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
				if(isset($post['files'])){ $bericht->fileid = $post['files'][0]; }
				if($new){ $bericht->creationdate = new Phalcon\Db\RawValue('now()');  } 
				else { $bericht->creationdate = date('Y-m-d H:i:s',strtotime($bericht->creationdate)); }
				$bericht->lastedit = new Phalcon\Db\RawValue('now()');   
				$bericht->userid = $this->user['id'];  
						
				
				if(isset($post['tags']) && is_array($post['tags']))
				{
					//delete previous choices
					foreach(BerichtTag::find('berichtid = "'.$bericht->id.'"') as $berichttag)
					{	if($berichttag->delete() == false)
						{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
							array_push($status['messages'],$message);	}
					}		
				
				
					foreach($post['tags'] as $tag)
					{
						$berichttag = BerichtTag::findFirst('berichtid = "'.$bericht->id.'" AND tagid = "'.$tag.'"');
						if(!isset($berichttag->id))
						{  
							$berichttag = new BerichtTag();	
							$berichttag->id = new Phalcon\Db\RawValue('uuid()');
							$berichttag->berichtid = $bericht->id;
							$berichttag->tagid = $tag;
							if($berichttag->save())
							{ }
							else
							{
								foreach ($berichttag->getMessages() as $message)
								{
									//	echo $message;
								}
							}
						}		
					}
				}
					
				$cc=0;	
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$messages = $bericht->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
				
				if($bericht->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($bericht->getMessages() as $message)
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
			$bericht = Bericht::findFirst('id = "'.$post['id'].'"');
			if($bericht->delete())
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

		$bericht = Bericht::findFirst('id = "'.$id.'"');
		$this->view->setVar("bericht", $bericht);
		$this->view->setVar("entityid", $bericht->id);
		
		$tabs = array('berichtreactie');
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					//start berichtreactie stuff 
					
					$actions = $this->actionauth('berichtreactie');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "berichtreactie"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("berichtreactiecolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Berichtreactie';
					$args['args'] = 'berichtid = "'.$bericht->id.'"';
					$args['search'] = false;
					
					$indexberichtreacties = $this->search2($args); 		
					$this->view->setVar("indexberichtreacties", $indexberichtreacties);
					
					//end berichtreactie stuff
					
					
					//start bericht_tag stuff 	
			
					$actions = $this->actionauth('Tag');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "tag"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("tagcolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'BerichtTag';
					$args['args'] = 'berichtid = "'.$bericht->id.'"';
					$args['search'] = false;
					$berichttags = $this->search2($args); 
					$indextags->items = array();
				
					//relation to entity
					foreach($berichttags->items as $berichttag)
					{ $indextags->items[] = $berichttag->Tag;	}
					$indextags->total_pages = $berichttags->total_pages;

					$this->view->setVar("indextags", $indextags);
					
					//end bericht_tag stuff
					
		
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
			$allcolumns = array('titel','slug','creationdate','lastedit','beschrijving');
			$table = Column::findFirst('entity = "bericht"');
			$columns = $table->columns;		

			$args['entity'] = 'bericht';
			$args['columns'] = array('titel','slug','creationdate','lastedit','beschrijving');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$berichts = $this->search2($args); 

			if(count($berichts) > 0)
			{ 				
				$status['goto'] = $this->csv($berichts,$columns,'bericht');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
