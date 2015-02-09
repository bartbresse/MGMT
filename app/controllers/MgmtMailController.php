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

class MgmtMailController extends ControllerBase
{
	protected $entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('email');
        Phalcon\Tag::setTitle('MgmtMails');
        parent::initialize();
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'MgmtMail';
	}
	
	public function indexAction()
    {			
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$MgmtMails = $this->search2(array('columns' => array_keys(MgmtMail::columnMap()),'entity' => $this->entity)); 
		if(count($MgmtMails) > 0){ $this->view->setVar("MgmtMails", $MgmtMails); }
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
		$MgmtMails = $this->search($columns,'MgmtMail',$args); 
		if(count($MgmtMails) > 0)
		{ 
			$this->view->setVar("MgmtMails", $MgmtMails); 
		}		
		$this->view->partial("MgmtMail/clean"); 	
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
			$args['entity'] = 'MgmtMail';
			$args['columns'] = array_keys(MgmtMail::columnMap());	
			
			$MgmtMails = $this->search2($args);
			if(count($MgmtMails) > 0)
			{
				$this->view->setVar("MgmtMails", $MgmtMails); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("mgmtmail/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$MgmtMail = MgmtMail::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('MgmtMail',$MgmtMail->id);		
		$this->view->setVar("files", $files);	

		$cm = $MgmtMail->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $MgmtMail->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$form->add(new TextEditor("message", array("id" => "message","value" => $MgmtMail->message,"class" => "form-control")));
					
		
		$this->view->setVar("MgmtMail", $MgmtMail);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("mgmtmail/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$MgmtMail = MgmtMail::findFirst();
		if(!isset($MgmtMail->id))
		{
			$MgmtMail = new MgmtMail();
			$MgmtMail->userid = $this->uuid(); 
		}
		
		$cm = $MgmtMail->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		$form->add(new TextEditor("message", array("id" => "message","value" => $MgmtMail->message,"class" => "form-control")));
		$form->add(new FileUpload('file',array('id'=>'file','class'=>'form-control','x' => 200,'y' => 200)));
		
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
			$validation = $this->striptagsfilter($validation,array("id","subject","message","from","to","lastedit"));
			
			
					$validation->add('subject', new PresenceOf(array(
						
						'field' => 'subject', 
						'message' => 'Het veld subject is verplicht.'
					)));

					$validation->add('subject', new StringLength(array(
						'messageMinimum' => 'Vul een subject in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('message', new PresenceOf(array(
						
						'field' => 'message', 
						'message' => 'Het veld message is verplicht.'
					)));

					$validation->add('message', new StringLength(array(
						'messageMinimum' => 'Vul een message in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('from', new PresenceOf(array(
						
						'field' => 'from', 
						'message' => 'Het veld from is verplicht.'
					)));

					$validation->add('from', new StringLength(array(
						'messageMinimum' => 'Vul een from in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('to', new PresenceOf(array(
						
						'field' => 'to', 
						'message' => 'Het veld to is verplicht.'
					)));

					$validation->add('to', new StringLength(array(
						'messageMinimum' => 'Vul een to in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('mgmtmail',$post);
				
				//save standard columns 
				foreach ($entity as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && strlen($post[$key]) > 0)
					{ 
						$entity->$key = $post[$key]; 
					}				
				}
				
				//if(isset($post['files'])){ $user->fileid = $post['files'][0]; } 	
					
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
							echo 'mgmtmail:'.$message;
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
			$MgmtMail = MgmtMail::findFirst('id = "'.$post['id'].'"');			
			if($MgmtMail->delete())
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
