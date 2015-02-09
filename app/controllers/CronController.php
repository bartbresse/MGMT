<?php

use Phalcon\Tag as Tag;

class CronController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction()
    {
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		//TODO verify request from server
		if(true) 
		{
                    //BACKUP
                    if($this->backup())
                    {
                        $status['status'] = 'ok';
                    }
                    //TODO CHECK FOR UPDATES
		}
		echo json_encode($status);
    }
	
	private function backup()
	{
		$config = new Phalcon\Config\Adapter\Ini('../app/config/config.ini');

		//collect all files and package
		ini_set("memory_limit","300M");
		
		$folder = '../../';
		
		$a = new PharData('website'.date("d-m-y").'.tar');
		$a->buildFromDirectory($folder.'/app');
		$a->buildFromDirectory($folder.'/public');
		
		if($a->compress(Phar::GZ))
		{
			'website'.date("d-m-y").'.tar.gz';
		}

		//generate database dump
		$filedb = 'backupdb.sql.gz';
		$command = "mysqldump --opt -h'".$config->database->host."' -u'".$config->database->username."' -p'".$config->database->password."' 'dev12hw_hwff' | gzip -9 > ".$filedb;
		exec($command);
		
		//return new file($file);
		//upload to dropbox
		//files
		$f = fopen('website'.date("d-m-y").'.tar.gz', "rb");
		$this->dbx->uploadFile('/website'.date("d-m-y").'.tar.gz', Dropbox\WriteMode::add(), $f);		
		//database
		$f = fopen('backupdb.sql.gz', "rb");
		$this->dbx->uploadFile('/backupdb.sql.gz', Dropbox\WriteMode::add(), $f);		
		
		//destroy backup files after upload
		unlink('website'.date("d-m-y").'.tar');
		unlink('website'.date("d-m-y").'.tar.gz');
		unlink($filedb);
	
		//on backup send confirmation
		/*$email = array(
		'html'       => $content, 
		'subject'    => 'Backup geslaagd',
		'from_email' => 'bart@hetworks.nl',
		'from_name'  => 'localhost',
		'to'         => array(array('adres' => 'bart@hetworks.nl'))
		);*/

		//$result = $this->mandrill->messages_send($email);	
		//TODO set up a google drive connection as well
		
		return true;
	}
}
