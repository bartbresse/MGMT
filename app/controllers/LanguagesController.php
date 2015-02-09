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

class LanguagesController extends ControllerBase
{
	protected $entity;
	protected $status = array('status' => 'false','messages' => array());	
	protected $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('settings');
        Phalcon\Tag::setTitle('Languages');
        parent::initialize();
		$this->setModule('settings');
		
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Languages';
	}
	
	public function indexAction()
    {
		$mgmtform = new MgmtUtils\MgmtForm();
		$this->view->setVar("mgmtform", $mgmtform);
	
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Languagess = $this->search2(array('columns' => array_keys(Languages::columnMap()),'entity' => $this->entity)); 
		if(count($Languagess) > 0){ $this->view->setVar("Languagess", $Languagess); }
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
		
		$Languagess = $this->search($columns,'Languages',$args); 
		if(count($Languagess) > 0)
		{ 
			$this->view->setVar("Languagess", $Languagess); 
		}		
		$this->view->partial("Languages/clean"); 	
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
			$args['entity'] = 'Languages';
			$args['columns'] = array_keys(Languages::columnMap());	
			
			$Languagess = $this->search2($args);
			if(count($Languagess) > 0)
			{
				$this->view->setVar("Languagess", $Languagess); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("languages/clean"); 	
		}
	}
	
	public function gridAction()
	{
		$this->view->disable();
		if($this->request->isPost()) 
		{
			$post = $this->post;
			
			$this->setaction($this->entity);
			$this->setcolumns($this->entity);
		
			$args = $this->getargs($post);
			$args['entity'] = 'Languages';
			$args['columns'] = array_keys(Languages::columnMap());	
			
			$Languagess = $this->search2($args);
			if(count($Languagess) > 0)
			{
				$this->view->setVar("Languagess", $Languagess); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("languages/grid"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Languages = Languages::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('Languages',$Languages->id);		
		$this->view->setVar("files", $files);	

		$cm = $Languages->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Languages->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$this->view->setVar("Languages", $Languages);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("languages/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Languages = Languages::findFirst();
		if(!isset($Languages->id))
		{
			$Languages = new Languages();
			$Languages->userid = $this->uuid(); 
		}
		
		$cm = $Languages->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
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
			$validation = $this->striptagsfilter($validation,array("id","eng"));
			
			
					$validation->add('eng', new PresenceOf(array(
						
						'field' => 'eng', 
						'message' => 'Het veld eng is verplicht.'
					)));

					$validation->add('eng', new StringLength(array(
						'messageMinimum' => 'Vul een eng in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('languages',$post);
				
				//save standard columns 
				foreach ($entity as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
					{ 
						$entity->$key = $post[$key]; 
					}				
				}
				
				
				
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
							echo 'languages:'.$message;
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
			$Languages = Languages::findFirst('id = "'.$post['id'].'"');			
			if($Languages->delete())
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
				
		$user = User::findFirst('id = "'.$id.'"');
		$this->view->setVar("user", $user);
		$this->view->setVar("entityid", $user->id);
		
		$acl = Acl::findFirst();		
		$this->view->setVar("acl", $acl);
		
		$cm = $acl->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $acl->$column,'id' => $column,'class' => 'form-control'))); }	

		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
	}
}
?>
