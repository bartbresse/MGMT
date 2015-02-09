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

class MgmtmoduleController extends ControllerBase
{
	protected $entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('settings');
        Phalcon\Tag::setTitle('Modules');
        parent::initialize();
		$this->setModule('settings');
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'MgmtModule';
	}
	
	public function indexAction()
    {			
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$MgmtModules = $this->search2(array('columns' => array_keys(MgmtModule::columnMap()),'entity' => $this->entity)); 
		if(count($MgmtModules) > 0){ $this->view->setVar("MgmtModule", $MgmtModules); }
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
		
		$MgmtModules = $this->search($columns,'MgmtModule',$args); 
		if(count($MgmtModules) > 0)
		{ 
			$this->view->setVar("MgmtModules", $MgmtModules); 
		}		
		$this->view->partial("MgmtModule/clean"); 	
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
			$args['entity'] = 'MgmtModule';
			$args['columns'] = array_keys(MgmtModule::columnMap());	
			
			$MgmtModule = $this->search2($args);
			if(count($MgmtModule) > 0)
			{
				$this->view->setVar("MgmtModules", $MgmtModules); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("MgmtModule/clean"); 	
		}
	}
	
	
	private function create()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		
		//files
		$files = $this->getfiles('MgmtModule',$MgmtModule->id);		
		$this->view->setVar("files", $files);	
		
		$cm = $MgmtModule->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $MgmtModule->$column,'id' => $column,'class' => 'form-control'))); }	
	}
	
	public function editAction()
	{
		
		$id = $this->request->getQuery('id');
		$MgmtModule = MgmtModule::findFirst('id = "'.$id.'"');
		
		
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		
		//files
		$files = $this->getfiles('MgmtModule',$MgmtModule->id);		
		$this->view->setVar("files", $files);	
		
		

		$cm = $MgmtModule->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $MgmtModule->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$this->view->setVar("MgmtModule", $MgmtModule);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("MgmtModule/new");	
	}
	
	public function newAction()
	{
		
		

		$MgmtModule = new MgmtModule();
		
		
		$form = new Form();
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$cm = $MgmtModule->columnMap();
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
			$validation = $this->striptagsfilter($validation,array("firstname","insertion","lastname","email","password","password2","postcode","city","streetnumber","telephone","verification"));
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$user = $this->getUser('user',$post);
				
				//save standard columns 
				foreach ($user as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && strlen($post[$key]) > 0)
					{ 
						$user->$key = $post[$key]; 
					}				
				}
				
				
				$user = $this->newpassword($user,$post);
				
				if(isset($post['files'])){ $user->fileid = $post['files'][0]; } 	
					
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$status['messages'] = $this->getmessages($user->validation());	
				}

				if(count($status['messages'])==0 && $user->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($user->getMessages() as $message)
					{
							echo 'user:'.$message;
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
			$MgmtModule = MgmtModule::findFirst('id = "'.$post['id'].'"');			
			if($MgmtModule->delete())
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
