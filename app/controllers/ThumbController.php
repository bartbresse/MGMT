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

class ThumbController extends ControllerBase
{
    protected $entity;
    
    public function initialize()
    {
        $this->view->setTemplateAfter('media');
        Phalcon\Tag::setTitle('Thumbs');
        parent::initialize();
        $this->setModule('media');
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        $this->session = $this->session->get('auth');
        $this->entity = 'MgmtThumb';
    }
	
    public function indexAction()
    {			
        $this->setaction($this->entity);
        $this->setcolumns($this->entity);
        $Thumbs = $this->search2(array('columns' => array_keys(MgmtThumb::columnMap()),'entity' => $this->entity)); 
        if(count($Thumbs) > 0){ $this->view->setVar("Thumbs", $Thumbs); }
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

        $Thumbs = $this->search($columns,'MgmtThumb',$args); 
        if(count($Thumbs) > 0)
        { 
                $this->view->setVar("Thumbs", $Thumbs); 
        }		
        $this->view->partial("Thumb/clean"); 	
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

            $Thumbs = $this->search2($args);
            if(count($Thumbs) > 0)
            {
                $this->view->setVar("Thumbs", $Thumbs); 
            }

            $this->view->setvar("post", $post);
            $this->view->partial("thumb/clean"); 	
        }
    }
	
	public function editAction()
	{
		$form = new Form();
		$post = $this->post;
		$entityid = $this->uuid();
		$id = $this->request->getQuery('id');

		$Thumb = MgmtThumb::findFirst('id = "'.$id.'"');
		//files
		$files = $this->getfiles('MgmtThumb',$Thumb->id);		
		$this->view->setVar("files", $files);	

		$cm = $Thumb->columnMap();
		foreach($cm as $column)
		{ $form->add(new Text($column, array('value' => $Thumb->$column,'id' => $column,'class' => 'form-control'))); }	
		//TODO add special fields
		
		$this->view->setVar("Thumb", $Thumb);		
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
		$this->view->pick("thumb/new");	
	}
	
	public function newAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$Thumb = MgmtThumb::findFirst();
		if(!isset($Thumb->id))
		{
			$Thumb = new Thumb();
			$Thumb->userid = $this->uuid(); 
		}
		
		$cm = $Thumb->columnMap();
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
			$validation = $this->striptagsfilter($validation,array("id","titel","x","y"));
			
			$validation->add('titel', new PresenceOf(array(
				
				'field' => 'titel', 
				'message' => 'Het veld titel is verplicht.'
			)));

			$validation->add('titel', new StringLength(array(
				'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
				'min' => 2
			)));
		
			$validation->add('x', new PresenceOf(array(
				
				'field' => 'x', 
				'message' => 'Het veld x is verplicht.'
			)));

			$validation->add('x', new StringLength(array(
				'messageMinimum' => 'Vul een x in van tenminste 2 characters.',
				'min' => 2
			)));
		
			$validation->add('y', new PresenceOf(array(
				
				'field' => 'y', 
				'message' => 'Het veld y is verplicht.'
			)));

			$validation->add('y', new StringLength(array(
				'messageMinimum' => 'Vul een y in van tenminste 2 characters.',
				'min' => 2
			)));
				
			
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{
				$entity = $this->getEntity('MgmtThumb',$post);
				
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
							echo 'thumb:'.$message;
					}
				}		
			}
		}
		echo json_encode($status);
	}

	public function generateAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$filetree = new MgmtFile\MgmtFileTree();
			$filetree->generateStructure();

			$files = File::find();
			$sizes = MgmtThumb::find();
			foreach($files as $file)
			{
                            $image = new MgmtFile\MgmtImage($file);
                            $image->generateThumbs($sizes);
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
			$Thumb = MgmtThumb::findFirst('id = "'.$post['id'].'"');			
			if($Thumb->delete())
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
