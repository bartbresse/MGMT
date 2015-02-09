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

class DocumentationController extends ControllerBase
{
    public function initialize()
    {
        Phalcon\Tag::setTitle('Documentation');
        parent::initialize();
        $this->setModule('documentation');
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        $this->session = $this->session->get('auth');
        $this->entity = '';

        $mgmtform = new MgmtUtils\MgmtForm();
        $this->view->setVar("mgmtform", $mgmtform);
    }
	
    public function indexAction()
    {			
        
    }
}
?>
