<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class TagController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Tags');
        parent::initialize();
    }

    public function indexAction()
    {		
		//$actions = array('edit','delete');	
		$actions = $this->actionauth('Tag');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "tag"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("entity"); }
		$this->view->setVar("columns", $columns);	
		
		$allcolumns = array('titel','slug','lastedit','creationdate','entity');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	
		$columns = array('titel','slug','lastedit','creationdate','entity');		
		$tags = $this->search($columns,'Tag'); 
		if(count($tags) > 0){ $this->view->setVar("tags", $tags); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$actions = $this->actionauth('Bericht');
		$this->view->setVar("actions", $actions);		

		$table = Column::findFirst('entity = "tag"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("entity"); }
		$this->view->setVar("tagcolumns", $columns);
		$this->view->setVar("columns", $columns);			
		
		$allcolumns = array('titel','slug','lastedit','creationdate','entity');
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
		$columns = array('titel','slug','lastedit','creationdate','entity');		
		
		if(isset($post['field']) && isset($post['value']))
		{
			$args = $post['field'].' = "'.$post['value'].'"';
		}
		
		$tags = $this->search($columns,'Tag',$args); 
		if(count($tags) > 0){ $this->view->setVar("indextags", $tags); }		
		$this->view->partial("tag/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$actions = array('edit','delete');
			$post = $this->request->getPost();
			$table = Column::findFirst('entity = "tag"');
			$allcolumns = array('titel','slug','lastedit','creationdate','entity');
			$allcolumns = $table->getallorderedcolumns($allcolumns);
			
			if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("entity"); }	

			$this->view->setVar("actions", $actions);
			$this->view->setVar("allcolumns", $allcolumns);	
			$this->view->setVar("tagcolumns", $columns);	

			$args['entity'] = 'tag';
			$args['columns'] = array('titel','slug','lastedit','creationdate','entity');
			
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
				$this->view->setVar("indextags", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("tag/clean"); 	
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
				$tag = Tag::findFirst('id = "'.$post['id'].'"');
			}
			else
			{
				$tag = Tag::findFirst();
			}	
		
			$cm = $tag->columnMap();
		
			$this->view->setVar("tag", $tag);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $tag->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

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

			$tag = Tag::findFirst('id = "'.$post['id'].'"');
			
			//save standard columns 
			foreach ($tag as $key => $value)
			{
				if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
				{ 
					$user->$key = $post[$key]; 
				}				
			}		
			if($tag->save())
			{
				$status['status'] = 'ok';
			}
			else
			{
				foreach ($tag->getMessages() as $message)
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

		$tag = Tag::findFirst('id = "'.$id.'"');
		$cm = $tag->columnMap();
		
		$this->view->setVar("tag", $tag);
		
		

		//relation
		$berichts = Bericht::find();
		$this->view->setVar("berichts", $berichts);

		//relations
		$tagberichts = BerichtTag::find('tagid = "'.$tag->id.'"');
		$rberichts = array();

		foreach($tagberichts as $tagbericht)
		{ array_push($rberichts,$tagbericht->berichtid);	}
		$this->view->setVar("tagberichts",$rberichts);

					
				
	
		//relation
		$events = Event::find();
		$this->view->setVar("events", $events);

		//relations
		$tagevents = EventTag::find('tagid = "'.$tag->id.'"');
		$revents = array();

		foreach($tagevents as $tagevent)
		{ array_push($revents,$tagevent->eventid);	}
		$this->view->setVar("tagevents",$revents);

					
				
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $tag->$column,'id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('',$this->check('entity'));
									$resultset = Entity::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Entity::find();
								}
								$this->view->setVar("entitys",$resultset);	
								

		
		
		
		
		
		$this->view->setVar("form", $form);
		$this->view->pick("tag/new");	
	}

	public function newAction()
	{
		$form = new Form();
		$session = $this->session->get('auth');

		$tag = Tag::findFirst();
		if(!isset($tag->id))
		{
			$tag = new Tag();
			$tag->userid = $session['id'];
		}
		
		$cm = $tag->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
			//special field line controller.php line:95
								if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
								if($sessionuser['clearance'] < 900)
								{
									$q = $this->orquery('',$this->check('entity'));
									$resultset = Entity::find(array('conditions' => $q));
								}
								else
								{
									$resultset = Entity::find();
								}
								$this->view->setVar("entitys",$resultset);	
									
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
		
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
						//special field line controller.php line:95
						if(!isset($sessionuser)){ $sessionuser = $this->session->get('auth'); }
						if($sessionuser['clearance'] < 900)
						{
							$q = $this->orquery('',$this->check('event'));
							$resultset = Event::find(array('conditions' => $q));
						}
						else
						{
							$resultset = Event::find();
						}
						$this->view->setVar("events",$resultset);	
					
		
		
		
		$this->view->setVar("form", $form);
	}

	public function addAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$get = $this->request->getQuery();
			
			$validation = new Phalcon\Validation();
	
			$validation->setFilters("titel", array("string", "striptags"));	
			$validation->setFilters("entity", array("string", "striptags"));	 

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
				$tagx = Tag::findFirst('id = "'.$post['id'].'"'); 
				if(!isset($tagx->id))
				{ 		 
					$tag = new Tag();
					if(strlen($post['id']) < 5){ $tag->id = $this->uuid(); }else{ $tag->id = $post['id']; }
					$new = true;
				}
				else
				{
					$tag = $tagx;
				}
				
				//save standard columns 
				foreach ($tag as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid')
					{ 
						$tag->$key = $post[$key]; 
					}				
				}
				
				$tag->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); $tag->userid = $this->user['id'];  
				$tag->lastedit = new Phalcon\Db\RawValue('now()');   
				if(!isset($tagx->id)){ $tag->creationdate = new Phalcon\Db\RawValue('now()');  } 
								
					
					if(isset($post['berichts']) && is_array($post['berichts']))
					{
						//delete previous choices
						foreach(BerichtTag::find('berichtid = "'.$bericht->id.'"') as $berichttag)
						{	if($berichttag->delete() == false)
							{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
								array_push($status['messages'],$message);	}
						}		
					
					
						foreach($post['berichts'] as $bericht)
						{
							$berichttag = BerichtTag::findFirst('tagid = "'.$tag->id.'" AND berichtid = "'.$bericht.'"');
							if(!isset($berichttag->id))
							{  
								$berichttag = new BerichtTag();	
								$berichttag->id = new Phalcon\Db\RawValue('uuid()');
								$berichttag->tagid = $tag->id;
								$berichttag->berichtid = $bericht;
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
					
						
					
					if(isset($post['events']) && is_array($post['events']))
					{
						//delete previous choices
						foreach(EventTag::find('eventid = "'.$event->id.'"') as $eventtag)
						{	if($eventtag->delete() == false)
							{ 	$message['MGMTSYSTEM'] = 'SYSTEM ERROR: UNABLE TO DELETE ROW';
								array_push($status['messages'],$message);	}
						}		
					
					
						foreach($post['events'] as $event)
						{
							$eventtag = EventTag::findFirst('tagid = "'.$tag->id.'" AND eventid = "'.$event.'"');
							if(!isset($eventtag->id))
							{  
								$eventtag = new EventTag();	
								$eventtag->id = new Phalcon\Db\RawValue('uuid()');
								$eventtag->tagid = $tag->id;
								$eventtag->eventid = $event;
								if($eventtag->save())
								{ }
								else
								{
									foreach ($eventtag->getMessages() as $message)
									{
										//	echo $message;
									}
								}
							}		
						}
					}
					
				$cc=0;	
				if($new)
				{
					$messages = $tag->validation();
					for($i=0;$i<count($messages);$i++)
					{
						$message[$messages[$i]->getField()] = $messages[$i]->getMessage();
						array_push($status['messages'],$message);
						$cc++;
					}
				}
				
				if($tag->save() && $cc==0)
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($tag->getMessages() as $message)
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
			$tag = Tag::findFirst('id = "'.$post['id'].'"');
			if($tag->delete())
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

		$tag = Tag::findFirst('id = "'.$id.'"');
		$this->view->setVar("tag", $tag);
		$this->view->setVar("entityid", $tag->id);
		
		$tabs = array();
		$this->view->setVar("tabs", $tabs);
		
		
		
		
					
					//start bericht_tag stuff 	
			
					$actions = $this->actionauth('Bericht');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "bericht"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("berichtcolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'BerichtTag';
					$args['args'] = 'tagid = "'.$tag->id.'"';
					$args['search'] = false;
					$berichttags = $this->search2($args); 
					$indexberichts->items = array();
				
					//relation to entity
					foreach($berichttags->items as $berichttag)
					{ $indexberichts->items[] = $berichttag->Bericht;	}
					$indexberichts->total_pages = $berichttags->total_pages;

					$this->view->setVar("indexberichts", $indexberichts);
					
					//end bericht_tag stuff
					
					
					//start event_tag stuff 	
			
					$actions = $this->actionauth('Event');
					$this->view->setVar("actions", $actions);		

					$table = Column::findFirst('entity = "event"');
					if(isset($table->columns)){ $columns = $table->columns;	 }else{ $columns = array("creationdate"); }
					$this->view->setVar("eventcolumns", $columns);	
				
					$allcolumns = array('titel','slug','lastedit','creationdate');
					$this->view->setVar("allcolumns", $allcolumns);	
			
			
					$args['entity'] = 'EventTag';
					$args['args'] = 'tagid = "'.$tag->id.'"';
					$args['search'] = false;
					$eventtags = $this->search2($args); 
					$indexevents->items = array();
				
					//relation to entity
					foreach($eventtags->items as $eventtag)
					{ $indexevents->items[] = $eventtag->Event;	}
					$indexevents->total_pages = $eventtags->total_pages;

					$this->view->setVar("indexevents", $indexevents);
					
					//end event_tag stuff
					
		
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
			$allcolumns = array('titel','slug','lastedit','creationdate','entity');
			$table = Column::findFirst('entity = "tag"');
			$columns = $table->columns;		

			$args['entity'] = 'tag';
			$args['columns'] = array('titel','slug','lastedit','creationdate','entity');
			$args['order'] = $post['order'];
			$args['search'] = $post['search'];				
			$args['rows'] = 1000000;	
			
			$tags = $this->search2($args); 

			if(count($tags) > 0)
			{ 				
				$status['goto'] = $this->csv($tags,$columns,'tag');		
				$status['status'] = 'ok';		
			}
		}
		echo json_encode($status);
	}


	#startuserspecific#

							 #enduserspecific#

}
?>
