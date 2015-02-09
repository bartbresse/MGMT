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

class ModuleController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('settings');
        Phalcon\Tag::setTitle('Module');
        parent::initialize();
            $this->setModule('settings');
            $this->post = $this->request->getPost();
            $this->get =  $this->request->getQuery();
            $this->session = $this->session->get('auth');
            $this->entity = 'MgmtModule';

            $mgmtform = new MgmtUtils\MgmtForm();
            $this->view->setVar("mgmtform", $mgmtform);
	}
	
	public function indexAction()
    {			
		$this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Modules = $this->search2(array('columns' => array_keys(MgmtModule::columnMap()),'entity' => $this->entity)); 
		if(count($Modules) > 0){ $this->view->setVar("Modules", $Modules); }
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
                
                $Modules = $this->search2(array('columns' => array_keys(MgmtModule::columnMap()),'entity' => 'MgmtModule','args' => $args)); 
		if(count($Modules) > 0)
		{ 
                    $this->view->setVar("Modules", $Modules); 
		}		
		$this->view->partial("module/clean"); 	
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
			
			$Modules = $this->search2($args);
			if(count($Modules) > 0)
			{
				$this->view->setVar("Modules", $Modules); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("module/clean"); 	
		}
	}
	
	public function editAction()
	{
            $form = new Form();
            $post = $this->post;
            $entityid = $this->uuid();
            $id = $this->request->getQuery('id');

            $Module = MgmtModule::findFirst('id = "'.$id.'"');
            //files
            $files = $this->getfiles('Module',$Module->id);		
            $this->view->setVar("files", $files);	

            $cm = $Module->columnMap();
            foreach($cm as $column)
            { $form->add(new Text($column, array('value' => $Module->$column,'id' => $column,'class' => 'form-control'))); }	
            //TODO add special fields

            $this->view->setVar("Module", $Module);		
            $this->view->setVar("entityid", $entityid);
            $this->view->setVar("form", $form);
            $this->view->pick("module/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Module = MgmtModule::findFirst();
		if(!isset($Module->id))
		{
			$Module = new Module();
			$Module->userid = $this->uuid(); 
		}
		
		$cm = $Module->columnMap();
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
			$validation = $this->striptagsfilter($validation,array("id","icon"));
			
			
                        $validation->add('icon', new PresenceOf(array(

                                'field' => 'icon', 
                                'message' => 'Het veld icon is verplicht.'
                        )));

                        $validation->add('icon', new StringLength(array(
                                'messageMinimum' => 'Vul een icon in van tenminste 2 characters.',
                                'min' => 2
                        )));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
                            $entity = $this->getEntity('MgmtModule',$post);

                            //save standard columns 
                            foreach ($entity as $key => $value)
                            {
                                    if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
                                    { 
                                            $entity->$key = $post[$key]; 
                                    }				
                            }

                             $entity->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $post['titel'])); 


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
                                                    echo 'module:'.$message;
                                    }
                            }

                            $entity = new MgmtEntity\MgmtEntity($post['titel']);
                            $entity->alias = $post['titel'];
                            $entity->clearance = 400;
                            $entity->newtext = $post['titel'];
                            $entity->comment = 'new mgmt module';
                            $entity->module = $post['titel'];

                            $module = new MgmtEntity\MgmtModule($entity);
                            $module->generate();
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
			$Module = MgmtModule::findFirst('id = "'.$post['id'].'"');			
			if($Module->delete())
			{
                            $entity = new MgmtEntity\MgmtEntity($post['titel']);
                            $entity->alias = $Module->titel;
                            $entity->clearance = 400;
                            $entity->newtext = $Module->titel;
                            $entity->comment = 'new mgmt module';
                            $entity->module = $Module->titel;

                            $module = new MgmtEntity\MgmtModule($entity);
                            $module->delete();
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
