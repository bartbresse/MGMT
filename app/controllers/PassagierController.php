<?php
use Phalcon\Forms\Form,
	Phalcon\Forms\FileUpload,
	Phalcon\Forms\TextEditor,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class PassagierController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Passagiers');
        parent::initialize();
		$this->setModule('public');
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Passagier';
		
		$mgmtform = new MgmtUtils\MgmtForm();
		$this->view->setVar("mgmtform", $mgmtform);
	}
	
	public function indexAction()
    {			
		$this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Passagiers = $this->search2(array('columns' => array_keys(Passagier::columnMap()),'entity' => $this->entity)); 
		if(count($Passagiers) > 0){ $this->view->setVar("Passagiers", $Passagiers); }
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
	
		if(isset($post['field']) && isset($post['value']))
		{
			$acl = Acl::find('entityid = "'.$post['value'].'" ');
			$cc=0;$str = '';
			foreach($acl as $rule)
			{
				if($cc>0){$str .= ' OR '; }
				$str .= ' id = "'.$rule->userid.'" ';
				$cc++;
			}
			$args = $str;
		}
                
                $Passagiers = $this->search2(array('columns' => array_keys(Passagier::columnMap()),'entity' => 'passagier','args' => $args)); 
		if(count($Passagiers) > 0)
		{ 
                    $this->view->setVar("Passagiers", $Passagiers); 
		}		
		$this->view->partial("passagier/clean"); 	
	}
	
	public function sortAction()
	{
		$this->view->disable();
		if($this->request->isPost()) 
		{
			$post = $this->post;
			
			$this->setaction($this->entity);
			$this->setcolumns($this->entity);
		
			$args = $this->getargs($post);
			$args['entity'] = 'Passagier';
			$args['columns'] = array_keys(Passagier::columnMap());	
			
			$Passagiers = $this->search2($args);
			if(count($Passagiers) > 0)
			{
				$this->view->setVar("Passagiers", $Passagiers); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("passagier/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Passagier = Passagier::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('Passagier',$Passagier->id);		
		$this->view->setVar("files", $files);	

		$cm = $Passagier->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Passagier->$column,'id' => $column,'class' => 'form-control5272'))); }	
		//TODO add special fields
		
		$form->add(new Text("creationdate", array("value" => $Passagier->creationdate,"id" => "creationdate","class" => "form-control5272 datetime")));
					$form->add(new TextEditor("text", array("id" => "text","value" => $Passagier->text,"class" => "form-control5272")));
					$form->add(new Select("planeid",Plane::find() ,array("using" => array("id","titel"),"value" => $Passagier->planeid ,"id" => "planeid","class" => "form-control5272 select")));
					
		
		$this->view->setVar("Passagier", $Passagier);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("passagier/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Passagier = Passagier::findFirst();
		if(!isset($Passagier->id))
		{
			$Passagier = new Passagier();
			$Passagier->userid = $this->uuid(); 
		}
		
		$cm = $Passagier->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control5272'))); }	
		
		$form->add(new Text("creationdate", array("value" => $Passagier->creationdate,"id" => "creationdate","class" => "form-control5272 datetime")));
					$form->add(new TextEditor("text", array("id" => "text","value" => $Passagier->text,"class" => "form-control5272")));
					$form->add(new Select("planeid",Plane::find() ,array("using" => array("id","titel"),"value" => $Passagier->planeid ,"id" => "planeid","class" => "form-control5272 select")));
					
		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
	}

	public function addAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$post = $this->post;
			
			$validation = new Phalcon\Validation();
			$validation = $this->striptagsfilter($validation,array("planeid","id","titel","text"));
			
			
					$validation->add('titel', new PresenceOf(array(
						
						'field' => 'titel', 
						'message' => 'Het veld titel is verplicht.'
					)));

					$validation->add('titel', new StringLength(array(
						'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('text', new PresenceOf(array(
						
						'field' => 'text', 
						'message' => 'Het veld text is verplicht.'
					)));

					$validation->add('text', new StringLength(array(
						'messageMinimum' => 'Vul een text in van tenminste 2 characters.',
						'min' => 2
					)));
				
                                        $validation->add('planeid', new PresenceOf(array(

                                                'field' => 'planeid', 
                                                'message' => 'Het veld plane is verplicht.'
                                        )));

                                        $validation->add('planeid', new StringLength(array(
                                                'messageMinimum' => 'Selecteer een plane.',
                                                'min' => 2
                                        )));
                                    
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('passagier',$post);
				
				//save standard columns 
				foreach ($entity as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
					{ 
						$entity->$key = $post[$key]; 
					}				
				}
				
				if($this->new){ $entity->creationdate = date("H:i:s Y-m-d"); }
                                
				
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$status['messages'] = $this->getmessages($entity->validation());	
				}

				if(count($status['messages'])==0 && $entity->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($entity->getMessages() as $message)
					{
							echo 'passagier:'.$message;
					}
				}		
			}
		}
		echo json_encode($status);
	}

	public function deleteAction()
	{
		$this->view->disable();
		$status = $this->status;
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$Passagier = Passagier::findFirst('id = "'.$post['id'].'"');			
			if($Passagier->delete())
			{
				$status['status'] = 'ok';
			}
		}
		echo json_encode($status);
	}

	public function viewAction()
	{
		$form = new Form();
		$post = $this->post;	
		$id = $this->get['id'];
				
		$entity = Passagier::findFirst('id = "'.$id.'"');
		$this->view->setVar("entity", $entity);
		$this->view->setVar("entityid", $entity->id);
		
		$acl = Acl::findFirst();		
		$this->view->setVar("acl", $acl);
		
		$cm = $acl->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $acl->$column,'id' => $column,'class' => 'form-control'))); }	
                
		$this->view->setVar("form", $form);
	}
}
?>
