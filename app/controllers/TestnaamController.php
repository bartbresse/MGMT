<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class TestnaamController extends ControllerBase
{
	private $entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Testnaams');
        parent::initialize();
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Testnaam';
	}
	
	public function indexAction()
    {			
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Testnaams = $this->search2(array('columns' => array_keys(Testnaam::columnMap()),'entity' => $this->entity)); 
		if(count($Testnaams) > 0){ $this->view->setVar("Testnaams", $Testnaams); }
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
		
		$Testnaams = $this->search($columns,'Testnaam',$args); 
		//$Testnaams = $this->search2(array('columns' => array_keys(User::columnMap()),'entity' => $this->entity,'args' => $args)); 
		if(count($Testnaams) > 0)
		{ 
			$this->view->setVar("Testnaams", $Testnaams); 
		}		
		$this->view->partial("Testnaam/clean"); 	
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
			$args['entity'] = 'user';
			$args['columns'] = array_keys(User::columnMap());	
			
			$users = $this->search2($args);
			if(count($users) > 0)
			{
				$this->view->setVar("users", $users); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("user/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$user = User::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('user',$user->id);		
		$this->view->setVar("files", $files);	

		$cm = $user->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $user->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$form->add(new Password("password", array("id" => "password","class" => "form-control")));
		$form->add(new Password("password2", array("id" => "password2","class" => "form-control")));

		$this->view->setVar("user", $user);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("user/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$user = User::findFirst();
		if(!isset($user->id))
		{
			$user = new User();
			$user->userid = $this->uuid(); 
		}
		
		$cm = $user->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		$form->add(new Password("password", array("id" => "password","class" => "form-control")));
		$form->add(new Password("password2", array("id" => "password2","class" => "form-control")));
		$form->add(new FileUpload('gebruikerfoto',array('slot' => 0,'x' => 210,'y' => 210,'cx' => 200,'cy' => 200,'id' => 23,'crop' => 'true')));
		
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
			
			$user = User::findFirst('id = "'.$post['id'].'"'); 
			if(!$user)
			{
				$validation->add('password', new PresenceOf(array(
					'field' => 'specificaties',
					'message' => 'Het veld password is verplicht.'
				)));

				$validation->add('password', new StringLength(array(
					'messageMinimum' => 'Vul een password in van tenminste 2 characters.',
					'min' => 2
				)));
			}
		
			$validation->add('email', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld email is verplicht.'
			)));

			$validation->add('email', new StringLength(array(
				'messageMinimum' => 'Vul een email in van tenminste 2 characters.',
				'min' => 2
			)));

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
			$Testnaam = Testnaam::findFirst('id = "'.$post['id'].'"');			
			if($Testnaam->delete())
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
