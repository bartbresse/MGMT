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

class MgmtlanguageController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('settings');
        Phalcon\Tag::setTitle('Languages');
        parent::initialize();
		$this->setModule('settings');
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Mgmtlanguage';
		
		$mgmtform = new MgmtUtils\MgmtForm();
		$this->view->setVar("mgmtform", $mgmtform);
	}
	
	public function indexAction()
    {			
		$this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Mgmtlanguages = $this->search2(array('columns' => array_keys(Mgmtlanguage::columnMap()),'entity' => $this->entity)); 
		if(count($Mgmtlanguages) > 0){ $this->view->setVar("Mgmtlanguages", $Mgmtlanguages); }
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
                
                $Mgmtlanguages = $this->search2(array('columns' => array_keys(Mgmtlanguage::columnMap()),'entity' => 'mgmtlanguage','args' => $args)); 
		if(count($Mgmtlanguages) > 0)
		{ 
                    $this->view->setVar("Mgmtlanguages", $Mgmtlanguages); 
		}		
		$this->view->partial("mgmtlanguage/clean"); 	
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
			$args['entity'] = 'Mgmtlanguage';
			$args['columns'] = array_keys(Mgmtlanguage::columnMap());	
			
			$Mgmtlanguages = $this->search2($args);
			if(count($Mgmtlanguages) > 0)
			{
				$this->view->setVar("Mgmtlanguages", $Mgmtlanguages); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("mgmtlanguage/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Mgmtlanguage = Mgmtlanguage::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('Mgmtlanguage',$Mgmtlanguage->id);		
		$this->view->setVar("files", $files);	

		$cm = $Mgmtlanguage->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Mgmtlanguage->$column,'id' => $column,'class' => 'form-control8975'))); }	
		//TODO add special fields
		
		
		
		$this->view->setVar("Mgmtlanguage", $Mgmtlanguage);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("mgmtlanguage/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Mgmtlanguage = Mgmtlanguage::findFirst();
		if(!isset($Mgmtlanguage->id))
		{
			$Mgmtlanguage = new Mgmtlanguage();
			$Mgmtlanguage->userid = $this->uuid(); 
		}
		
		$cm = $Mgmtlanguage->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control8975'))); }	
		
		
		
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
			$validation = $this->striptagsfilter($validation,array("id","en","nl","fr","de"));
			
			
					$validation->add('en', new PresenceOf(array(
						
						'field' => 'en', 
						'message' => 'Het veld en is verplicht.'
					)));

					$validation->add('en', new StringLength(array(
						'messageMinimum' => 'Vul een en in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('nl', new PresenceOf(array(
						
						'field' => 'nl', 
						'message' => 'Het veld nl is verplicht.'
					)));

					$validation->add('nl', new StringLength(array(
						'messageMinimum' => 'Vul een nl in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('fr', new PresenceOf(array(
						
						'field' => 'fr', 
						'message' => 'Het veld fr is verplicht.'
					)));

					$validation->add('fr', new StringLength(array(
						'messageMinimum' => 'Vul een fr in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('de', new PresenceOf(array(
						
						'field' => 'de', 
						'message' => 'Het veld de is verplicht.'
					)));

					$validation->add('de', new StringLength(array(
						'messageMinimum' => 'Vul een de in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('mgmtlanguage',$post);
				
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
							echo 'mgmtlanguage:'.$message;
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
			$Mgmtlanguage = Mgmtlanguage::findFirst('id = "'.$post['id'].'"');			
			if($Mgmtlanguage->delete())
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
				
		$entity = Mgmtlanguage::findFirst('id = "'.$id.'"');
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
