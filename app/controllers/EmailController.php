<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class EmailController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Email');
        parent::initialize();
    }

    public function indexAction()
    {	
		
	}

	public function templateAction()
	{	
		$form = new Form();
		
		$user = User::findFirst();
		if(!isset($user->id))
		{
			$user = new User();
		}
		
		$cm = $user->columnMap();
		
		foreach($cm as $column)
		{ $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
		
		//TODO add special fields
		$form->add(new Password("password", array("id" => "password","class" => "form-control")));
		$form->add(new Password("password2", array("id" => "password2","class" => "form-control")));

		$files = File::find();
		$this->view->setVar("files", $files);

		//email
		$form->add(new Text("subject", array("subject" => "bcc","class" => "form-control")));
		$form->add(new Text("bcc", array("id" => "bcc","class" => "form-control")));
		$this->view->setVar("form", $form);
		
		$entityid = $this->uuid();
		$this->view->setVar("entityid", $entityid);
                
	}
}
