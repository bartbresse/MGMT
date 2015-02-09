<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Welcome');
        parent::initialize();
		$this->setModule('public');
    }

    public function indexAction()
    {
		
	}


	protected function connect()
	{
		
	}

	public function startAction()
	{
		echo 'start';
	}

	public function initAction()
	{
		$this->view->disable();
		$status = array('status' => 'false');	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
		
			//get all tables
			$statement = $this->db->prepare('SHOW TABLES');
			$result = $this->db->executePrepared($statement);
			//get relations			
			//load default templates
			die();
		}
		echo json_encode($status);
	}
}
