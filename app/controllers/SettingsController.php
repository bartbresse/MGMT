<?php
//require '../apps/common/library/Mollie/API/Autoloader.php';
use Phalcon\Forms\Form,
	Phalcon\Forms\FileUpload,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Check,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;
	
class SettingsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Settings');
        parent::initialize();
        $this->setModule('settings');

        $this->status = array('status' => 'false','messages' => array());
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        $this->session = $this->session->get('auth');
        $this->entity = 'MgmtEntity2';
    }

    public function indexAction()
    {
       
    }
}
