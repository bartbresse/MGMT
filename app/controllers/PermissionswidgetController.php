<?php
use Phalcon\Forms\Form,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Textarea,
    Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\Password;

use Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\StringLength;

class PermissionswidgetController extends ControllerBase
{
	private $entity;
	private $Entity;
	private $status = array('status' => 'false','messages' => array());	
	private $post;
	private $get;
	private $session;

    public function initialize()
    {
        $this->view->setTemplateAfter('entity');
        Phalcon\Tag::setTitle('Emailwidget');
        parent::initialize();
		$this->post = $this->request->getPost();
		$this->get =  $this->request->getQuery();
		$this->session = $this->session->get('auth');
		$this->entity = 'user';
		$this->Entity = 'User';	
	}

	
	private function setcolumns($entity)
	{
		//ordered columns zorgt voor de volgorde van de kolommen	
		$table = Column::findFirst('entity = "'.$entity.'"');
		if(isset($table->columns)){ $columns = $table->orderedcolumns;	 }else{ $columns = array("verification"); }
		$this->view->setVar("columns", $columns);
	
		//getallordered columns zorgt voor de volgorde de selectie weergave van de kolommen
		$Entity = ucfirst($entity);
		$allcolumns = array_keys($Entity::columnMap());
		$allcolumns = $table->getallorderedcolumns($allcolumns);
		$this->view->setVar("allcolumns", $allcolumns);	
	}
	
	private function setaction($entity)
	{
		$actions = $this->actionauth($entity);
		$this->view->setVar("actions", $actions);	
	}

    public function indexAction()
    {	
	
	}
	
	public function cleanAction()
	{
		$this->view->disable();
		$post = $this->request->getPost();
		$form = new Form();

		$rechten = array('1000' => 'Hetworker','900' => 'Landelijke beheerder','500' => 'Lokale beheerder','10' => 'Bezoeker');
		$allow = array();
		
		$session = $this->session;
		$keys = array_keys($rechten);
		foreach($keys as $key)
		{	if($session['clearance'] > $key)
			{	$allow[$key] = $rechten[$key];	}
		}
		
		$form->add(new Select('permission-rechten',$allow,array('value' => '','id' => 'permission-rechten','class' => 'form-control event-multiple-select')));
		$form->add(new Select('permission-beheerfunctie',Category::find(),array('using' => array('id','titel'),'multiple' => 'multiple','value' => '','id' => 'permission-beheerfunctie','class' => 'form-control permission-rechten-permission-beheerfunctie')));		
	
		$this->view->setVar("form", $form);
		
		$this->view->partial("permissionswidget/clean"); 			
	}
	
	public function addAction()
	{
	
		//TODO FINISH ADD ACTION
		/*
			PERMISSION ADD START
		*/
		
		if(!isset($post)){ $post = $this->request->getPost(); } 
		$acl = Acl::find('userid = "'.$user->id.'" AND entity = "category"');
		foreach($acl as $a)
		{
			$a->delete();
		}
		
		if(isset($post['permission-beheerfunctie']) && is_array($post['permission-beheerfunctie']) && count($post['permission-beheerfunctie']) > 0)
		{
			$beheerfuncties = $post['permission-beheerfunctie'];
			foreach($beheerfuncties as $functie)
			{
				$acl = new Acl();
				$acl->id = $this->uuid();
				$acl->entityid = $functie;
				$acl->entity = 'category';
				$acl->userid = $user->id;
				$acl->clearance = 900;
				$acl->end = 1;
				$acl->request = 1;
				if($acl->save())
				{ }
				else
				{
					foreach ($acl->getMessages() as $message)
					{
						echo $message;
					}
				}
			}
		}
		
		if(isset($post['permission-rechten']))
		{
			$user->clearance = $post['permission-rechten'];
		}
		/*
			PERMISSION ADD END
		*/
	}
}
