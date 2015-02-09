<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;
	


class EmailsettingsController extends ControllerBase
{
    public function initialize()
    {
		$this->view->setTemplateAfter('entity');
		
        Phalcon\Tag::setTitle('Settings');
        parent::initialize();
		
		$this->status = array('status' => 'false','messages' => array(),'column' => array());
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'MgmtEntity';
		$this->lang = new MgmtLang();
	}

	public function indexAction()
	{
		echo 'hello';
	}
	
	public function newAction()
	{
		$this->view->setVar("mgmtoptions",$this->getMGMTcolumns());
		$this->view->setVar("options",$this->getOptions());
	}
}
?>
