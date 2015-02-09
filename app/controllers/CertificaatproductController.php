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

class CertificaatproductController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Certificaatproduct');
        parent::initialize();
		$this->setModule('public');
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Certificaatproduct';
		
		$mgmtform = new MgmtUtils\MgmtForm();
		$this->view->setVar("mgmtform", $mgmtform);
	}
	
	public function indexAction()
    {			
		$this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Certificaatproducts = $this->search2(array('columns' => array_keys(Certificaatproduct::columnMap()),'entity' => $this->entity)); 
		if(count($Certificaatproducts) > 0){ $this->view->setVar("Certificaatproducts", $Certificaatproducts); }
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
                
                $Certificaatproducts = $this->search2(array('columns' => array_keys(Certificaatproduct::columnMap()),'entity' => 'certificaatproduct','args' => $args)); 
		if(count($Certificaatproducts) > 0)
		{ 
                    $this->view->setVar("Certificaatproducts", $Certificaatproducts); 
		}		
		$this->view->partial("certificaatproduct/clean"); 	
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
			$args['entity'] = 'Certificaatproduct';
			$args['columns'] = array_keys(Certificaatproduct::columnMap());	
			
			$Certificaatproducts = $this->search2($args);
			if(count($Certificaatproducts) > 0)
			{
				$this->view->setVar("Certificaatproducts", $Certificaatproducts); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("certificaatproduct/clean"); 	
		}
	}
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Certificaatproduct = Certificaatproduct::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('Certificaatproduct',$Certificaatproduct->id);		
		$this->view->setVar("files", $files);	

		$cm = $Certificaatproduct->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Certificaatproduct->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$form->add(new TextEditor("opmerking", array("id" => "opmerking","value" => $Certificaatproduct->opmerking,"class" => "form-control5926")));
					$form->add(new Select("productid",Product::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->productid ,"id" => "productid","class" => "form-control5926 select")));
					$form->add(new Select("Certificaatid",Certificaat::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->Certificaatid ,"id" => "Certificaatid","class" => "form-control5926 select")));
					$form->add(new Select("statusid",Status::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->statusid ,"id" => "statusid","class" => "form-control5926 select")));
					
		
		$this->view->setVar("Certificaatproduct", $Certificaatproduct);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("certificaatproduct/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Certificaatproduct = Certificaatproduct::findFirst();
		if(!isset($Certificaatproduct->id))
		{
			$Certificaatproduct = new Certificaatproduct();
			$Certificaatproduct->userid = $this->uuid(); 
		}
		
		$cm = $Certificaatproduct->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		$form->add(new TextEditor("opmerking", array("id" => "opmerking","value" => $Certificaatproduct->opmerking,"class" => "form-control5926")));
					$form->add(new Select("productid",Product::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->productid ,"id" => "productid","class" => "form-control5926 select")));
					$form->add(new Select("Certificaatid",Certificaat::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->Certificaatid ,"id" => "Certificaatid","class" => "form-control5926 select")));
					$form->add(new Select("statusid",Status::find() ,array("using" => array("id","titel"),"value" => $Certificaatproduct->statusid ,"id" => "statusid","class" => "form-control5926 select")));
					
		
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
			$validation = $this->striptagsfilter($validation,array("productid","certificaatid","statusid","id","opmerking"));
			
			
					$validation->add('opmerking', new PresenceOf(array(
						
						'field' => 'opmerking', 
						'message' => 'Het veld opmerking is verplicht.'
					)));

					$validation->add('opmerking', new StringLength(array(
						'messageMinimum' => 'Vul een opmerking in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('certificaatproduct',$post);
				
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
							echo 'certificaatproduct:'.$message;
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
			$Certificaatproduct = Certificaatproduct::findFirst('id = "'.$post['id'].'"');			
			if($Certificaatproduct->delete())
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
