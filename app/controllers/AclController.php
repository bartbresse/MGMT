<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class AclController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Berichts');
        parent::initialize();
    }

    public function indexAction()
    {		
		
	}



	public function templateAction() 
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$form = new Form();
		
			if(isset($post['id'])){ $acl = Acl::findFirst('id = "'.$post['id'].'"'); }
			if(isset($acl->id))
			{ }
			else
			{	$acl = Acl::findFirst();	}	
			$cm = $acl->columnMap();
		
			$this->view->setVar("acl", $acl);
		
			foreach($cm as $column)
			{ $form->add(new Text($column, array('value' => $acl->$column,'id' => $column,'class' => $post['template'].'-control'))); }	

			//TODO add special fields
			$form->add(new Textarea("html", array("id" => "html","class" => $post['template']."-control")));
			$form->add(new Textarea("footer", array("id" => "footer","class" => $post['template']."-control")));

			$templates = Template::find();
			$this->view->setVar("templates", $templates);
			$this->view->setVar("form", $form);
		
			$entityid = $this->uuid();
			$this->view->setVar("entityid", $entityid);
			$this->view->partial("file/".$post['template']); 	
		}
	}

	#startuserspecific#
	public function sendAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			$user = User::findFirst('id = "'.$post['entityid'].'"');
			if(isset($user->id))
			{
				$user->clearance = $post['clearance'];
				if($user->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach($user->getMessages() as $message)
					{
							echo $message;
					}
				}
			}
			else
			{
				$status['status'] = 'user not found';
			}		
		}
		echo json_encode($status);
	}
	#enduserspecific#
}
?>
