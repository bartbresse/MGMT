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

class MgmtemailtemplateController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('email');
        Phalcon\Tag::setTitle('Email templates');
        parent::initialize();
		$this->setModule('email');
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'Mgmtemailtemplate';
		
		$mgmtform = new MgmtUtils\MgmtForm();
		$this->view->setVar("mgmtform", $mgmtform);
	}
	
	public function indexAction()
    {			
		$this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$Mgmtemailtemplates = $this->search2(array('columns' => array_keys(Mgmtemailtemplate::columnMap()),'entity' => $this->entity)); 
		if(count($Mgmtemailtemplates) > 0){ $this->view->setVar("Mgmtemailtemplates", $Mgmtemailtemplates); }
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
                
                $Mgmtemailtemplates = $this->search2(array('columns' => array_keys(Mgmtemailtemplate::columnMap()),'entity' => 'mgmtemailtemplate','args' => $args)); 
		if(count($Mgmtemailtemplates) > 0)
		{ 
                    $this->view->setVar("Mgmtemailtemplates", $Mgmtemailtemplates); 
		}		
		$this->view->partial("mgmtemailtemplate/clean"); 	
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
			$args['entity'] = 'Mgmtemailtemplate';
			$args['columns'] = array_keys(Mgmtemailtemplate::columnMap());	
			
			$Mgmtemailtemplates = $this->search2($args);
			if(count($Mgmtemailtemplates) > 0)
			{
				$this->view->setVar("Mgmtemailtemplates", $Mgmtemailtemplates); 
			}

			$this->view->setvar("post", $post);
			$this->view->partial("mgmtemailtemplate/clean"); 	
		}
	}
	
        private function dataBuilder($form)
        {
            $form->add(new Select("entity", MgmtEntity2::find(),array('using' => array("id","name"),'id' => 'entity','value' => '',"class" => "data-builder-control")));
            $choices = array('findFirst' => 'Selecteer een bepaalde rij.',
                             'find' => 'Selecteer een aantal rijen.');
            
            $form->add(new Select("find", $choices,array('id' => 'find','value' => '',"class" => "data-builder-control")));
            $form->add(new Text("arguments",array('id' => 'arguments','value' => '',"class" => "data-builder-control")));
            
            return $form;
        } 
        
        public function selectcolumnsAction()
        {
            $this->view->disable();
            $status = $this->status;	
            if($this->request->isPost()) 
            {
                $post = $this->post;
                $entity = MgmtEntity2::findFirst('id = "'.$post['entity'].'"');
                
                $entityname = ucfirst($entity->name);
                $entitycolumns =  MgmtEntitycolumn::find('mgmtentityid = "'.$entity->id.'"');
                
                $columnmap = array_keys(MgmtEntitycolumn::columnMap());
                $columns = array();
                
                foreach($entitycolumns as $entitycolumn)
                {
                    if($entitycolumn->name != 'id')
                    {
                        array_push($columns,array($entity->name,$entitycolumn->name));
                    }
                }
                
                $relations = MgmtRelation::find('fromid = "'.$entity->id.'"');
                foreach($relations as $relation)
                {
                    //array_push($columns,$entitycolumn->name);
                    $entity2 = MgmtEntity2::findFirst('id = "'.$relation->toid.'"');
                    $entitycolumns2 =  MgmtEntitycolumn::find('mgmtentityid = "'.$relation->toid.'"');
                    foreach($entitycolumns2 as $entitycolumn2)
                    {
                        if($entitycolumn2->name != 'id')
                        {
                            array_push($columns,array($entity2->name,$entitycolumn2->name));
                        }
                    }
                }
                
                $status['name'] = $entity->name;
                $status['columns'] = $columns;
                $status['status'] = 'ok';	
            }
            echo json_encode($status);            
        } 
        
        private function htmltable()
        {
            
        }
        
        public function addEntityAction()
        {
            $this->view->disable();
            $status = $this->status;	
            $random = rand( 1000 , 9999 );
            
            if($this->request->isPost()) 
            {
                $html = '<table>';
                $post = $this->post;
                $keys = array_keys($post);
                
                if($post['find'] == 'findFirst')
                {
                    foreach($keys as $key)
                    {
                        switch($key)
                        {
                            case 'entity':
                            case 'find':
                            case 'arguments':
                                break;
                            default:
                                if($post[$key] == 1)
                                {
                                    if($post['find'] == 'findFirst')
                                    {
                                        $html .= '<tr><td>['.$random.'>'.$post['find'].':'.$key.']</td></tr>';         
                                    }
                                    else
                                    {
                                        $html .= '';
                                    }
                                }
                                break;            
                        }    
                    }
                }
                else if($post['find'] == 'find')
                {
                    
                    $html = '[loop]<table><tr>';
                    foreach($keys as $key)
                    {
                        if($post[$key] == 1){  
                           
                            $exp = explode(':',$key);
                            $html .= '<td>'.ucfirst($exp[1]).'</td>'; 
                           
                        }
                    }
                    $html .= '</tr><tr>';
                    foreach($keys as $key)
                    {
                        if($post[$key] == 1){  $html .= '<td>['.$random.'>'.$post['find'].':'.$key.']</td>'; }
                    }
                    $html .= '</tr></table>[/loop]';
                }
                
                $html .= '</html>';
                
                $status['status'] = 'ok';
                $status['html'] = $html;
            }
            echo json_encode($status);           
        }
        
        
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Mgmtemailtemplate = Mgmtemailtemplate::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('Mgmtemailtemplate',$Mgmtemailtemplate->id);		
		$this->view->setVar("files", $files);	

		$cm = $Mgmtemailtemplate->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Mgmtemailtemplate->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$form->add(new TextEditor("inhoud", array("id" => "inhoud","value" => $Mgmtemailtemplate->inhoud,"class" => "form-control7405")));
		 $form = $this->dataBuilder($form);			
		
		$this->view->setVar("Mgmtemailtemplate", $Mgmtemailtemplate);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("mgmtemailtemplate/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Mgmtemailtemplate = Mgmtemailtemplate::findFirst();
		if(!isset($Mgmtemailtemplate->id))
		{
			$Mgmtemailtemplate = new Mgmtemailtemplate();
			$Mgmtemailtemplate->userid = $this->uuid(); 
		}
		
		$cm = $Mgmtemailtemplate->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		$form->add(new TextEditor("inhoud", array("id" => "inhoud","value" => $Mgmtemailtemplate->inhoud,"class" => "form-control7405")));
		 $form = $this->dataBuilder($form);			
		
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
			$validation = $this->striptagsfilter($validation,array("id","inhoud","cc","bcc"));
			
			
					$validation->add('inhoud', new PresenceOf(array(
						
						'field' => 'inhoud', 
						'message' => 'Het veld inhoud is verplicht.'
					)));

					$validation->add('inhoud', new StringLength(array(
						'messageMinimum' => 'Vul een inhoud in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('cc', new PresenceOf(array(
						
						'field' => 'cc', 
						'message' => 'Het veld cc is verplicht.'
					)));

					$validation->add('cc', new StringLength(array(
						'messageMinimum' => 'Vul een cc in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('bcc', new PresenceOf(array(
						
						'field' => 'bcc', 
						'message' => 'Het veld bcc is verplicht.'
					)));

					$validation->add('bcc', new StringLength(array(
						'messageMinimum' => 'Vul een bcc in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('mgmtemailtemplate',$post);
				
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
							echo 'mgmtemailtemplate:'.$message;
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
			$Mgmtemailtemplate = Mgmtemailtemplate::findFirst('id = "'.$post['id'].'"');			
			if($Mgmtemailtemplate->delete())
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
