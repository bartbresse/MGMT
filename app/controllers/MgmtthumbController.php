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

class MgmtthumbController extends ControllerBase
{
	private $entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('media');
        Phalcon\Tag::setTitle('MgmtThumbs');
        parent::initialize();
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'MgmtThumb';
	}
	
	public function indexAction()
    {			
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$MgmtThumbs = $this->search2(array('columns' => array_keys(MgmtThumb::columnMap()),'entity' => $this->entity)); 
		if(count($MgmtThumbs) > 0){ $this->view->setVar("MgmtThumbs", $MgmtThumbs); }
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
		
		$MgmtThumbs = $this->search($columns,'MgmtThumb',$args); 
		if(count($MgmtThumbs) > 0)
		{ 
			$this->view->setVar("MgmtThumbs", $MgmtThumbs); 
		}		
		$this->view->partial("MgmtThumb/clean"); 	
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
			$args['entity'] = 'MgmtThumb';
			$args['columns'] = array_keys(MgmtThumb::columnMap());	
			
			$MgmtThumbs = $this->search2($args);
			if(count($users) > 0)
			{
				$this->view->setVar("MgmtThumbs", $MgmtThumbs); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("mgmtthumb/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$MgmtThumb = MgmtThumb::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('MgmtThumb',$MgmtThumb->id);		
		$this->view->setVar("files", $files);	

		$cm = $MgmtThumb->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $MgmtThumb->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		
		
		$this->view->setVar("MgmtThumb", $MgmtThumb);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("mgmtthumb/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$MgmtThumb = MgmtThumb::findFirst();
		if(!isset($MgmtThumb->id))
		{
			$MgmtThumb = new MgmtThumb();
			$MgmtThumb->userid = $this->uuid(); 
		}
		
		$cm = $MgmtThumb->columnMap();
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
			$MgmtThumb = MgmtThumb::findFirst('id = "'.$post['id'].'"');			
			if($MgmtThumb->delete())
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
