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
		$this->entity = 'MgmtEntity';
    }

    public function indexAction()
    {
		$this->setaction($this->entity);
		$this->setcolumns($this->entity);
		$entitys = $this->search2(array('columns' => array_keys(MgmtEntity::columnMap()),'entity' => $this->entity)); 
		if(count($entitys) > 0){ $this->view->setVar("entitys", $entitys); }	
    }
	
	public function index2Action()
	{
		
		
		
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
			$args['entity'] = $this->entity;
			$args['columns'] = array_keys(MgmtEntity::columnMap());	
			
			$entitys = $this->search2($args);
			if(count($entitys) > 0)
			{
				$this->view->setVar("entitys", $entitys); 
			}

			//sort post. used to remember the sorted column
			$this->view->setvar("post", $post);
			$this->view->partial("settings/clean"); 	
		}
	}
	
    public function pageAction()
    {
		$controllers = Controller::find();		
	 	$this->view->setVar("controllers", $controllers);

		$tables = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		$this->view->setVar("tables", $tables);	

		$notables = array('controller','controllerview','view','file','authentication','acl','controller','controllerview','email','entity');
		$this->view->setVar("notables", $notables);	
	}

	public function checklistsiteAction()
	{
		$controllers = Controller::find();		
	 	$this->view->setVar("controllers", $controllers);

		$tables = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		$this->view->setVar("tables", $tables);	

		$notables = array('controller','controllerview','view','file','authentication','acl','controller','controllerview','email','entity');
		$this->view->setVar("notables", $notables);			
	}

    public function entityAction()
    {
		$tables = array();
 		$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		
		$notables = array('acl','controller','controllerview','view','file','authentication','email','entity','column');
		$this->view->setVar("notables", $notables);

		$metaentities = array();
		foreach($tablesq as $tableq) 
		{
			$ext = explode('_',$tableq['Tables_in_'.DATABASENAME]);
			if(!isset($ext[1]) && !in_array($tableq['Tables_in_'.DATABASENAME],$notables))
			{
				array_push($tables,$tableq['Tables_in_'.DATABASENAME]);			
				$entity = MgmtEntity::findFirst('titel = "'.$tableq['Tables_in_'.DATABASENAME].'"');
				if(!$entity)
				{
					$entity = new MgmtEntity();
					$entity->titel = $tableq['Tables_in_'.DATABASENAME];
					$entity->slug = strtolower(preg_replace("/[^a-zA-Z]/", "", $tableq['Tables_in_'.DATABASENAME]));
					$entity->alias = $tableq['Tables_in_'.DATABASENAME];
					$entity->clearance = 1000;
					$entity->save();
				}
				array_push($metaentities,$entity);
			}
		}
		$this->view->setVar("tables", $metaentities);	
		
		//TODO automate this process all entities that can be coupled to a specific other entity need to be loaded here 
		/**

		REGISTER FUNCTIONS HERE & IN DB
		
		**/
		
		//email messages are send uppon: password rest, user confirmation, etc...
		$functions = array();
		$messages = Message::find();
		foreach($messages as $message)
		{
			//set entity this needs to be done in every case
			$message->entity = 'message';
			$message->func = 'email';
			$function = array();
			$function['entity'] = 'message';
			$function['func'] = 'message';
			$function['template'] = 'email';
			$function['titel'] = $message->titel;
			$function['id'] = $message->id;
			array_push($functions,$function);
		}
		
		//PERMISSION WIDGET
		/*<option value="<?=$function['entity'].'_'.$function['id'];?>"><?=$function['titel'];?></option>*/
		$function = array();
		$function['entity'] = 'acl';
		$function['func'] = 'acl';
		$function['titel'] = 'permissions';
		$function['template'] = 'permissions';
		$function['id'] = $this->uuid();
		array_push($functions,$function);
		
		//RELATIES LEDEN&BEHEERDER FAMILY FACTORY WIDGET
		$function = array();
		$function['entity'] = 'acl';
		$function['func'] = 'acl';
		$function['titel'] = 'relaties';
		$function['template'] = 'relaties';
		$function['id'] = $this->uuid();
		array_push($functions,$function);	
						
		//TAG CONNECT 
		$function = array();
		$function['entity'] = 'tag';
		$function['func'] = 'tag';
		$function['titel'] = 'generatetag';
		$function['template'] = 'generatetag';
		$function['id'] = $this->uuid();
		array_push($functions,$function);	
			
		$this->view->setVar("functions", $functions);
	
		$icons = array();
		$this->view->setVar("icons", $icons);
    }

	public function backupstatusAction()
	{
		
	}

	public function multilangAction()
	{
			
	}
	
	public function newentityAction()
	{
		$form = new Form();
		$session = $this->session;
		$post = $this->post;		
		$entityid = $this->uuid();
		
		$user = User::findFirst();
		if(!isset($user->id))
		{
			$user = new User();
			$user->userid = $this->uuid(); 
		}
		
		$form->add(new Text('titel', array('id' => 'titel','class' => 'form-control','placeholder' => 'titel')));
		$form->add(new Text('alias', array('id' => 'titel','class' => 'form-control','placeholder' => 'alias')));
		
		$count = 10;
		$this->view->setVar("count", $count);
		for($i=0;$i<$count;$i++)
		{
			$nullcolumn = array('id' => 'nullcolumn'.$i,'class' => 'form-control');
			$name = array('id' => 'name'.$i,'class' => 'form-control',"value" => "");
			$column = array("using" => array("id", "titel"),"id" => "column".$i,"class" => "form-control select-type");
			
			switch($i)
			{
				case 0;
					$mgmtcolumn = MgmtColumn::findFirst("titel = 'id'");
					$column['value'] = $mgmtcolumn->id;
					$column['readonly'] = 'readonly';
					
					$name['value'] = $mgmtcolumn->titel;	
					$name['readonly'] = 'readonly';
				
					$nullcolumn['disabled'] = 'disabled';
				break;
				case 1:
					$mgmtcolumn = MgmtColumn::findFirst("titel = 'authorid'");
					$column['value'] = $mgmtcolumn->id;
					$column['readonly'] = 'readonly';
					
					$name['value'] = $mgmtcolumn->titel;	
					$name['readonly'] = 'readonly';
				
					$nullcolumn['disabled'] = 'disabled';
				break;	
				case 2:
					$mgmtcolumn = MgmtColumn::findFirst("titel = 'creationdate'");
					$column['value'] = $mgmtcolumn->id;
					$column['readonly'] = 'readonly';
					
					$name['value'] = $mgmtcolumn->titel;	
					$name['readonly'] = 'readonly';
				
					$nullcolumn['disabled'] = 'disabled';
				break;
				case 3:
					$mgmtcolumn = MgmtColumn::findFirst("titel = 'lastedit'");
					$column['value'] = $mgmtcolumn->id;
					$column['readonly'] = 'readonly';
					
					$name['value'] = $mgmtcolumn->titel;	
					$name['readonly'] = 'readonly';
				
					$nullcolumn['disabled'] = 'disabled';
				break;
			}
			
			$form->add(new Check('nullcolumn'.$i, $nullcolumn));
			$form->add(new Check('uniquecolumn'.$i, array('id' => 'uniquecolumn'.$i,'class' => 'form-control')));
			$form->add(new Check('primarycolumn'.$i, array('id' => 'primarycolumn'.$i,'class' => 'form-control')));
			$form->add(new Text('name'.$i, $name));
			$form->add(new Select("column".$i, MgmtColumn::find() ,$column));
		}
	
		$this->view->setVar("entityid", $entityid);
		$this->view->setVar("form", $form);
	}
	
	
	
	
	private function getMGMTcolumns()
	{
		$html = '';
		$columns = MgmtColumn::find();
		foreach($columns as $column)
		{
			$html .= '<option value="'.$column->id.'">'.$column->titel.'</option>';
		}
		return $html;
	}
	
	private function getOptions()
	{
		$html = '';
		$options = array('VARCHAR','INT','BOOLEAN','DATETIME','DATE');
		foreach($options as $option)
		{
			$html .= '<option value="'.$option.'">'.$option.'</option>';
		}
		return $html;
	}
	
	public function newentityjsAction()
	{
		$this->view->setVar("mgmtoptions",$this->getMGMTcolumns());
		
		
		$this->view->setVar("options",$this->getOptions());
	}
	
	public function getjsonentityAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$status['mgmtcolumns'] = $this->getMGMTcolumns();
			$status['columns'] = $this->getOptions();
		}	
		echo json_encode($status);
	}
	
	
	
	
	public function getfieldAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$post = $this->post;
			$column = MgmtColumn::findFirst('id = "'.$post['id'].'"');
			
			$status['type'] = $column->type;
			$status['name'] = $column->titel;
			$status['nullcolumn'] = $column->nullcolumn;
			$status['primarycolumn'] = $column->primarycolumn;
			$status['uniquecolumn'] = $column->uniquecolumn;
			$status['bindingtitle'] = $column->bindingtitle;
			$status['num'] = $post['num'];
		}
		echo json_encode($status);
	}
	
	public function addentityAction()
	{
		$this->view->disable();
		$status = $this->status;	
		if ($this->request->isPost()) 
		{
			$post = $this->post;
			$validation = new Phalcon\Validation();
			$validation = $this->striptagsfilter($validation,array("firstname","insertion","lastname","email","password","password2","postcode","city","streetnumber","telephone","verification"));
		/*
			$validation->add('email', new PresenceOf(array(
				'field' => 'specificaties',
				'message' => 'Het veld email is verplicht.'
			)));

			$validation->add('email', new StringLength(array(
				'messageMinimum' => 'Vul een email in van tenminste 2 characters.',
				'min' => 2
			)));*/
/*
			$messages = $validation->validate($post);
			if (count($messages))
			{
				$status['messages'] = $this->getmessages($messages);
			}
			else
			{*/
				
				//save standard columns 
				$query = 'CREATE TABLE IF NOT EXISTS `'.$post['titel'].'` (';
				for($i=0;$i<$post['count'];$i++)
				{
					if(strlen($post['name'.$i]) > 0 && strlen($post['column'.$i]) > 0)
					{
						$column = MgmtColumn::findFirst('id = "'.$post['column'.$i].'"');
						if($post['nullcolumn'.$i] == 0)
						{
							$null = 'NOT';
						}
						else
						{
							$null = '';	
						}
						$query .= '`'.$post['name'.$i].'` '.$column->type.' '.$null.' NULL,';
					}
				}
				$query .= 'PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;';
				
				echo $query;
				
				
				//print_r($post);
				die();
				
				/*
				CREATE TABLE IF NOT EXISTS `event` (
				  `id` varchar(36) NOT NULL,
				  `titel` varchar(255) NOT NULL,
				  `beschrijving` text NOT NULL,
				  `userid` varchar(36) NOT NULL,
				  `slug` varchar(255) NOT NULL,
				  `creationdate` datetime NOT NULL,
				  `lastedit` datetime NOT NULL,
				  `start` datetime NOT NULL,
				  `einde` datetime NOT NULL,
				  `fileid` varchar(36) NOT NULL,
				  `category` varchar(255) DEFAULT NULL,
				  `categoryid` varchar(36) NOT NULL,
				  `locatie` varchar(255) DEFAULT NULL,
				  `aanmeldingen` varchar(255) DEFAULT NULL,
				  `status` int(11) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
				*/


				/*
				foreach ($user as $key => $value)
				{
					if(isset($post[$key]) && $key != 'id' && $key != 'fileid' && strlen($post[$key]) > 0)
					{ 
						$user->$key = $post[$key]; 
					}				
				}
				
				
				$user = $this->newpassword($user,$post);
				
				if(isset($post['files'])){ $user->fileid = $post['files'][0]; } 	
					
				if(!isset($post['id']) || strlen($post['id']) < 10)
				{
					$status['messages'] = $this->getmessages($user->validation());	
				}

				if(count($status['messages'])==0 && $user->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($user->getMessages() as $message)
					{
							echo 'user:'.$message;
					}
				}*/		
			
		}
		echo json_encode($status);
	}
	
	public function generatecontrollerAction()
	{
		$tables = array();
		$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		foreach($tablesq as $tableq) 
		{
				array_push($tables,$tableq['Tables_in_'.DATABASENAME]);			
		}
		$this->view->setVar("tables", $tables);	
	}


	public function clearnotusedfiles()
	{
		//get all used files
		$files = array();
		$tables = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		foreach($tables as $table)
		{
			$tablen = explode('_',$table['Tables_in_'.DATABASENAME]);
			if(isset($table['Tables_in_'.DATABASENAME]) && !isset($tablen[1])){
				$tablename = ucfirst($table['Tables_in_'.DATABASENAME]);
				$t = $tablename::find();
				if(isset($t[0]) && isset($t[0]->fileid)){		
				foreach($t as $row)
				{ 
					array_push($files,$row->fileid);	}
				}		
			}		
		}
		
				
		//get all file ids
		$f = File::find();
		$unusedfiles = array();
		foreach($f as $file)
		{
			if(!in_array($file->id,$files))
			{
				if(file_exists($file->path))
				{
					unlink($file->path);
				}
				
				if(!$file->delete())
				{
					die();
				}
				array_push($unusedfiles,$file->id);
			}
		}
	}

	public function permissionsAction()
	{
		$actions = array('create','edit','delete','sort','export','search');
		$this->view->setvar('actions',$actions);

		$tablesq = $this->db->fetchAll("SHOW TABLES", Phalcon\Db::FETCH_ASSOC);
		$noincludes = array('controller','controllerview','view','file','authentication','acl','email','column','entity');

		$tables = array();

		foreach($tablesq as $tableq)
		{
			if(!in_array($tableq['Tables_in_'.DATABASENAME],$noincludes))
			{ array_push($tables,$tableq['Tables_in_'.DATABASENAME]);	}							
		}

		$this->view->setvar('tables',$tables);	
		$profiles = array('turn feature off' => '100000000',
							'Hetworker' => 1000,
						   'Beheerder' => 900,
						   'Editor' => 500,
						   'Blogger' => 400,
						   'Gebruiker' => 300,
						   'Bezoeker' => 10);
		$this->view->setvar('profiles',$profiles);	
	}

	public function addpermissionsAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			$post = $this->request->getPost();
			foreach(array_keys($post) as $permission)
			{
				$per = explode('*',$permission);
				$acl = Acl::findFirst('end = 1 AND request = 1 AND entity = "'.$per[0].'" AND actie = "'.$per[1].'"');
				if(!isset($acl->id))
				{	
					$acl = new Acl;			
					$acl->id =  new Phalcon\Db\RawValue('uuid()');
				}

				$acl->entity = $per[0];
				$acl->userid = $post[$permission];
				$acl->end = 1;
				$acl->request = 1;
				$acl->actie = $per[1];
				if($acl->save())
				{
					$status['status'] = 'ok';
				}
				else
				{
					foreach ($acl->getMessages() as $message)
					{
						echo $message;
					}
				}		
			}						
		}
		echo json_encode($status);
	}

	public function backupAction()
	{
		$this->view->disable();
		$status = array('status' => 'false','messages' => array());	
		if ($this->request->isPost()) 
		{
			//remove all unused files
			$this->clearnotusedfiles();
		
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
			$email = array(
			'html'       => $content, 
			'subject'    => 'Backup geslaagd',
			'from_email' => 'bart@hetworks.nl',
			'from_name'  => 'localhost',
			'to'         => array(array('adres' => 'bart@hetworks.nl'))
			);

			$result = $this->mandrill->messages_send($email);	
			//TODO set up a google drive connection as well
		}
	}
	
	public function checklistAction()
	{
		$html = ''; 
		
	}
	
	public function clientAction()
	{
		$html = '';
	}
	
	public function emailtemplatesAction()
	{
		$html = ''; 
		
	}
	
	public function standardmessagesAction()
	{
		$html = '';	
		
	}
}
