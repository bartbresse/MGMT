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

class PlaneAspect extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Vliegtuigen');
        parent::initialize();
        $this->setModule('public');
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        $this->session = $this->session->get('auth');
        $this->entity = 'Plane';

        $mgmtform = new MgmtUtils\MgmtForm();
        $this->view->setVar("mgmtform", $mgmtform);
    }
	
    public function indexAction()
    {			
        $this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
        $this->setaction($this->entity);
        $this->setcolumns($this->entity);
        $Planes = $this->search2(array('columns' => array_keys(Plane::columnMap()),'entity' => $this->entity)); 
        if(count($Planes) > 0){ $this->view->setVar("Planes", $Planes); }
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

        $Planes = $this->search2(array('columns' => array_keys(Plane::columnMap()),'entity' => 'plane','args' => $args)); 
        if(count($Planes) > 0)
        { 
            $this->view->setVar("Planes", $Planes); 
        }		
        $this->view->partial("plane/clean"); 	
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
                $args['entity'] = 'Plane';
                $args['columns'] = array_keys(Plane::columnMap());	

                $Planes = $this->search2($args);
                if(count($Planes) > 0)
                {
                        $this->view->setVar("Planes", $Planes); 
                }

                $this->view->setvar("post", $post);
                $this->view->partial("plane/clean"); 	
        }
    }
	
    public function editAction()
    {
        $form = new Form();
        $post = $this->post;
        $entityid = $this->uuid();
        $id = $this->request->getQuery('id');

        $Plane = Plane::findFirst('id = "'.$id.'"');
        //files
        $files = $this->getfiles('Plane',$Plane->id);		
        $this->view->setVar("files", $files);	

        $cm = $Plane->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('value' => $Plane->$column,'id' => $column,'class' => 'form-control3312'))); }	
        //TODO add special fields

        $form->add(new TextEditor("beschrijving", array("id" => "beschrijving","value" => $Plane->beschrijving,"class" => "form-control3312")));
        $form->add(new FileUpload("fileid",array("id" => "fileid","value" => File::findFirst('id = "'.$Plane->fileid.'"'),"class" => "form-control3312","x" => 200,"y" => 200,"crop" => false)));
        $form->add(new Text("firstflight", array("value" => $Plane->firstflight,"id" => "firstflight","class" => "form-control3312 date")));
        $form->add(new Text("creationdate", array("value" => $Plane->creationdate,"id" => "creationdate","class" => "form-control3312 datetime")));
        $form->add(new Text("nextscheduledflight", array("value" => $Plane->nextscheduledflight,"id" => "nextscheduledflight","class" => "form-control3312 datetime")));

        $this->view->setVar("Plane", $Plane);		
        $this->view->setVar("entityid", $entityid);
        $this->view->setVar("form", $form);
        $this->view->pick("plane/new");	
    }
	
    public function newAction()
    {
        $form = new Form();
        $session = $this->session;
        $post = $this->post;		
        $entityid = $this->uuid();

        $Plane = Plane::findFirst();
        if(!isset($Plane->id))
        {
            $Plane = new Plane();
            $Plane->userid = $this->uuid(); 
        }

        $cm = $Plane->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('id' => $column,'class' => 'form-control3312'))); }	

        $form->add(new TextEditor("beschrijving", array("id" => "beschrijving","value" => $Plane->beschrijving,"class" => "form-control3312")));
        $form->add(new FileUpload("fileid",array("id" => "fileid","value" => File::findFirst('id = "'.$Plane->fileid.'"'),"class" => "form-control3312","x" => 200,"y" => 200,"crop" => false)));
        $form->add(new Text("firstflight", array("value" => $Plane->firstflight,"id" => "firstflight","class" => "form-control3312 date")));
        $form->add(new Text("creationdate", array("value" => $Plane->creationdate,"id" => "creationdate","class" => "form-control3312 datetime")));
        $form->add(new Text("nextscheduledflight", array("value" => $Plane->nextscheduledflight,"id" => "nextscheduledflight","class" => "form-control3312 datetime")));


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
			$validation = $this->striptagsfilter($validation,array("id","titel","slug","beschrijving","staartnummer","fileid","firstflight","nextscheduledflight"));
			
			
					$validation->add('titel', new PresenceOf(array(
						
						'field' => 'titel', 
						'message' => 'Het veld titel is verplicht.'
					)));

					$validation->add('titel', new StringLength(array(
						'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('beschrijving', new PresenceOf(array(
						
						'field' => 'beschrijving', 
						'message' => 'Het veld beschrijving is verplicht.'
					)));

					$validation->add('beschrijving', new StringLength(array(
						'messageMinimum' => 'Vul een beschrijving in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('staartnummer', new PresenceOf(array(
						
						'field' => 'staartnummer', 
						'message' => 'Het veld staartnummer is verplicht.'
					)));

					$validation->add('staartnummer', new StringLength(array(
						'messageMinimum' => 'Vul een staartnummer in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('fileid', new PresenceOf(array(
						
						'field' => 'fileid', 
						'message' => 'Het veld fileid is verplicht.'
					)));

					$validation->add('fileid', new StringLength(array(
						'messageMinimum' => 'Vul een fileid in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('firstflight', new PresenceOf(array(
						
						'field' => 'firstflight', 
						'message' => 'Het veld firstflight is verplicht.'
					)));

					$validation->add('firstflight', new StringLength(array(
						'messageMinimum' => 'Vul een firstflight in van tenminste 2 characters.',
						'min' => 2
					)));
				
					$validation->add('nextscheduledflight', new PresenceOf(array(
						
						'field' => 'nextscheduledflight', 
						'message' => 'Het veld nextscheduledflight is verplicht.'
					)));

					$validation->add('nextscheduledflight', new StringLength(array(
						'messageMinimum' => 'Vul een nextscheduledflight in van tenminste 2 characters.',
						'min' => 2
					)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('plane',$post);
				
				//save standard columns 
				foreach ($entity as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
					{ 
						$entity->$key = $post[$key]; 
					}				
				}
				
				 $entity->slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $post['titel'])); 
                                 $entity->firstflight = date("Y-m-d",strtotime($post["firstflight"]));
                                    if($this->new){ $entity->authorid = $this->user["id"]; }
                                if($this->new){ $entity->creationdate = date("H:i:s Y-m-d"); }
                                 $entity->nextscheduledflight = date("H:i:s Y-m-d",strtotime($post["nextscheduledflight"])); 
                                    
				
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
							echo 'plane:'.$message;
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
			$Plane = Plane::findFirst('id = "'.$post['id'].'"');			
			if($Plane->delete())
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
				
		$entity = Plane::findFirst('id = "'.$id.'"');
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