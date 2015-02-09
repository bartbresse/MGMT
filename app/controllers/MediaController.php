<?php

use Phalcon\Forms\Form,
	Phalcon\Forms\FileUpload,
	Phalcon\Forms\MultipleFileUpload,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;
	
class MediaController extends ControllerBase
{
    public function initialize()
    {
        Phalcon\Tag::setTitle('Media');
        parent::initialize();
        $this->setModule('media');
    }

    public function indexAction()
    {		
        $this->setaction('File');
        $this->setcolumns('File');

        $files = $this->search2(array('columns' => array_keys(File::columnMap()),'entity' => 'File')); 

        $form = new Form();
        $form->add(new MultipleFileUpload('file',array('id'=>'file','class'=>'form-control','x' => 200,'y' => 200,'url' => $this->url->get())));
        $form->add(new FileUpload('file2',array('id'=>'file2','class'=>'form-control','x' => 120,'y' => 79,'url' => $this->url->get())));

        $this->view->setVar("form", $form);

        if(count($files) > 0){ $this->view->setVar("files", $files); }
    }
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();

		$actions = $this->actionauth('Media');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "Media"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }
		$this->view->setVar("Mediacolumns", $columns);	
		
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
		
		$Medias = $this->search($columns,'Media',$args); 
		if(count($Medias) > 0){ $this->view->setVar("indexMedias", $Medias); }		
		$this->view->partial("Media/clean"); 	
	}
	
	public function thumbsAction()
	{
		$this->setaction('MgmtThumb');
		$this->setcolumns('MgmtThumb');
		$thumbs = $this->search2(array('columns' => array_keys(User::columnMap()),'entity' => 'MgmtThumb')); 
		if(count($thumbs) > 0){ $this->view->setVar("thumbs", $thumbs); }
		
	}
	
	//inline form prototype
	public function addthumbAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			
			$entity = new MgmtThumb();
			$entity->id = $this->uuid();
			$entity->titel = $post['titel'];
			$entity->x = $post['x'];
			$entity->y = $post['y'];
			$entity->crop = $post['crop'];
			if($entity->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				
			}
		}
		echo json_encode($status);
	}
	
	//prototype grid
	public function gridAction()
	{
		$this->view->disable();
		if($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			
			$this->setaction('File');
			$this->setcolumns('File');
		
			$args = $this->getargs($post);
			$args['entity'] = 'File';
			$args['columns'] = array_keys(File::columnMap());	
			
			$files = $this->search2($args);
			if(count($files) > 0)
			{
				$this->view->setVar("files", $files); 
			}
			
			$this->view->setvar("post", $post);
			$this->view->partial("media/grid"); 	
		}
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "Media"');
			
			$allcolumns = array('titel','slug','creationdate','lastedit','beschrijving');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("beschrijving"); }

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("Mediacolumns", $columns);	

			$args['entity'] = 'Media';
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
				$this->view->setVar("indexMedias", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("Media/clean"); 	
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
				$Media = Media::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$Media = Media::findFirst();
			}	
		
			$cm = $Media->columnMap();
		
			$this->view->setVar("Media", $Media);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $Media->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$Media = Media::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($Media as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($Media->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($Media->getMessages() as $message)
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

		$Media = File::findFirst('id = "'.$id.'"');
		$cm = $Media->columnMap();
		
		$this->view->setVar("Media", $Media);
		
		//files
		$files = $this->getfiles('Media',$Media->id);		
		$this->view->setVar("files", $files);	

		//relation
		$tags = Tag::find();
		$this->view->setVar("tags", $tags);

		//relations
		$Mediatags = MediaTag::find('Mediaid = "'.$Media->id.'"');
		$rtags = array();

		foreach($Mediatags as $Mediatag)
		{ array_push($rtags,$Mediatag->tagid);	}
		$this->view->setVar("Mediatags",$rtags);

		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Media->$column,'id' => $column,'class' => 'form-control'))); }	
		
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
		$this->view->pick("Media/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$file = File::findFirst();
		if(!isset($user->id))
		{
			$file = new File();
			$file->userid = $this->uuid(); 
		}
		
		$cm = $file->columnMap();
		foreach($cm as $column)
		{ 
			$form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); 
		}	
		$form->add(new FileUpload('file',array('id'=>'file','class'=>'form-control','x' => 200,'y' => 200)));
		
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
				$Mediax = Media::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($Mediax->id))
				{ 		 
					$Media = new Media();
					if(strlen($post['id']) < 5){ $Media->id = $this->uuid(); }else{ $Media->id = $post['id']; }
					$new = true;
				}
				else
				{
					$Media = $Mediax;
				}
				
				//save standard columns 
				foreach ($Media as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && $key != 'creationdate')
					{ 
						$Media->$key = $post[$key]; 
					}				
				}
				
				$Media->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel']));
				if(isset($post['files'])){ $Media->fileid = $post['files'][0]; }
				if($new){ $Media->creationdate = new Phalcon\Db\RawValue('now()');  } 
				else { $Media->creationdate = date('Y-m-d H:i:s',strtotime($Media->creationdate)); }
				$Media->lastedit = new Phalcon\Db\RawValue('now()');   
				$Media->userid = $this->user['id'];  
						
				
				if(isset($post['tags']) && is_array($post['tags']))
				{
					//delete previous choices
					foreach(MediaTag::find('Mediaid = "'.$Media->id.'"') as $Mediatag)
					{	if($Mediatag->delete() == false)
						{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
							array_push($status['messages'],$message);	}
					}		
				
				
					foreach($post['tags'] as $tag)
					{
						$Mediatag = MediaTag::findFirst('Mediaid = "'.$Media->id.'" AND tagid = "'.$tag.'"');
						if(!isset($Mediatag->id))
						{  
							$Mediatag = new MediaTag();	
							$Mediatag->id = new Phalcon\Db\RawValue('uuid()');
							$Mediatag->Mediaid = $Media->id;
							$Mediatag->tagid = $tag;
							if($Mediatag->save())
							{ }
							else
							{
								foreach ($Mediatag->getMessages() as $message)
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
					$messages = $Media->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}	
				
				if($Media->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($Media->getMessages() as $message)
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
			$Media = Media::findFirst('id = "'.$post['id'].'"');
			if($Media->delete())
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

		$Media = Media::findFirst('id = "'.$id.'"');
		$this->view->setVar("Media", $Media);
		$this->view->setVar("entityid", $Media->id);
		
		$tabs = array('Mediareactie');
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					//start Mediareactie stuff 
					
					$actions = $this->actionauth('Mediareactie');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "Mediareactie"');
					if(isset($table->columns)){ $columns = $table->columns;	}else{ $columns = array("creationdate"); }
					$this->view->setVar("Mediareactiecolumns", $columns);	
					
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
				
					$args['entity'] = 'Mediareactie';
					$args['args'] = 'Mediaid = "'.$Media->id.'"';
					$args['search'] = false;
					
					$indexMediareacties = $this->search2($args); 		
					$this->view->setVar("indexMediareacties", $indexMediareacties);
					
					//end Mediareactie stuff
					
					
					//start Media_tag stuff 	
			
					$actions = $this->actionauth('Tag');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "tag"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("tagcolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'MediaTag';
					$args['args'] = 'Mediaid = "'.$Media->id.'"';
					$args['search'] = false;
					$Mediatags = $this->search2($args); 
					$indextags->items = array();
				
					//relation to entity
					foreach($Mediatags->items as $Mediatag)
					{ $indextags->items[] = $Mediatag->Tag;	}
					$indextags->total_pages = $Mediatags->total_pages;

					$this->view->setVar("indextags", $indextags);
					
					//end Media_tag stuff
					
		
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
			$table = Column::findFirst('entity = "Media"');
			$columns = $table->columns;		

			$args['entity'] = 'Media';
			$args['columns'] = array('titel','slug','creationdate','lastedit','beschrijving');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$Medias = $this->search2($args); 

			if(count($Medias) > 0)
			{ 				
				$status['goto'] = $this->csv($Medias,$columns,'Media');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
