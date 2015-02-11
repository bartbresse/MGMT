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

class MgmtupdateController extends ControllerBase
{
    public function initialize()
    {
    $this->view->setTemplateAfter('settings');
    Phalcon\Tag::setTitle('Updates');
    parent::initialize();
            $this->setModule('settings');
            $this->post = $this->request->getPost();
            $this->get =  $this->request->getQuery();
            $this->session = $this->session->get('auth');
            $this->entity = 'Mgmtupdate';

            $mgmtform = new MgmtUtils\MgmtForm();
            $this->view->setVar("mgmtform", $mgmtform);
    }
	
    public function indexAction()
    {	
        /*
        $ftp = $this->ftp;
        if($ftp->isconnected)
        {

            $model = file_get_contents('http://www.dutchwebdesigners.com/mgmtcommand/updates/test.volt'); 
            $file = '../app/views/test.volt'; 

        //    file_put_contents($file,$model);
        //    chmod($file,0777); 


            //$path = $_SERVER['DOCUMENT_ROOT'].'/MGMT/backend/app/views/test.volt';
            $path = '/domains/deappdeveloper.nl/public_html/MGMT/backend/app/views/test.volt';
            $file = '../app/views/plane/test.volt';
            $directory = 'testftp';

        //    $ftp->makeDir($directory);
            $ftp->putFile($path,$file);
            $ftp->close();
        }

        /*
        // set up basic connection
        $conn_id = ftp_connect('www.deappdeveloper.nl');

        // login with username and password
        $login_result = ftp_login($conn_id,'admin','L35GUDoS');

        // upload a file
        if (ftp_put($conn_id, '/domains/deappdeveloper.nl/public_html/MGMT/backend/app/views/test.volt','../app/views/plane/test.volt' , FTP_ASCII)) {
         echo "successfully uploaded \n";
        } else {
         echo "There was a problem while uploading \n";
        }
*/

        $this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
        $this->setaction($this->entity);
        $this->setcolumns($this->entity);
        $Mgmtupdates = $this->search2(array('columns' => array_keys(Mgmtupdate::columnMap()),'entity' => $this->entity)); 
        if(count($Mgmtupdates) > 0){ $this->view->setVar("Mgmtupdates", $Mgmtupdates); }
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

            $Mgmtupdates = $this->search2(array('columns' => array_keys(Mgmtupdate::columnMap()),'entity' => 'mgmtupdate','args' => $args)); 
            if(count($Mgmtupdates) > 0)
            { 
                $this->view->setVar("Mgmtupdates", $Mgmtupdates); 
            }		
            $this->view->partial("mgmtupdate/clean"); 	
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
                    $args['entity'] = 'Mgmtupdate';
                    $args['columns'] = array_keys(Mgmtupdate::columnMap());	

                    $Mgmtupdates = $this->search2($args);
                    if(count($Mgmtupdates) > 0)
                    {
                            $this->view->setVar("Mgmtupdates", $Mgmtupdates); 
                    }

                    $this->view->setvar("post", $post);
                    $this->view->partial("mgmtupdate/clean"); 	
            }
    }
	
    public function editAction()
    {
            $form = new Form();
            $post = $this->post;
            $entityid = $this->uuid();
            $id = $this->request->getQuery('id');

            $Mgmtupdate = Mgmtupdate::findFirst('id = "'.$id.'"');
            //files
            $files = $this->getfiles('Mgmtupdate',$Mgmtupdate->id);		
            $this->view->setVar("files", $files);	

            $cm = $Mgmtupdate->columnMap();
            foreach($cm as $column)
            { $form->add(new Text($column, array('value' => $Mgmtupdate->$column,'id' => $column,'class' => 'form-control7896'))); }	
            //TODO add special fields

            $form->add(new Text("creationdate", array("value" => $Mgmtupdate->creationdate,"id" => "creationdate","class" => "form-control7896 datetime")));
                                    $form->add(new Text("lastedit", array("value" => $Mgmtupdate->lastedit,"id" => "lastedit","class" => "form-control7896 datetime")));
                                    $form->add(new Text("updatecompleted", array("value" => $Mgmtupdate->updatecompleted,"id" => "updatecompleted","class" => "form-control7896 datetime")));


            $this->view->setVar("Mgmtupdate", $Mgmtupdate);		
            $this->view->setVar("entityid", $entityid);
            $this->view->setVar("form", $form);
            $this->view->pick("mgmtupdate/new");	
    }
	
    public function newAction()
    {
        $form = new Form();
        $session = $this->session;
        $post = $this->post;		
        $entityid = $this->uuid();

        $Mgmtupdate = Mgmtupdate::findFirst();
        if(!isset($Mgmtupdate->id))
        {
            $Mgmtupdate = new Mgmtupdate();
            $Mgmtupdate->userid = $this->uuid(); 
        }

        $cm = $Mgmtupdate->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('id' => $column,'class' => 'form-control7896'))); }	

        $form->add(new Text("creationdate", array("value" => $Mgmtupdate->creationdate,"id" => "creationdate","class" => "form-control7896 datetime")));
                                $form->add(new Text("lastedit", array("value" => $Mgmtupdate->lastedit,"id" => "lastedit","class" => "form-control7896 datetime")));
                                $form->add(new Text("updatecompleted", array("value" => $Mgmtupdate->updatecompleted,"id" => "updatecompleted","class" => "form-control7896 datetime")));


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
                $validation = $this->striptagsfilter($validation,array("id","titel","lastedit","updatecompleted"));


                $validation->add('titel', new PresenceOf(array(

                        'field' => 'titel', 
                        'message' => 'Het veld titel is verplicht.'
                )));

                $validation->add('titel', new StringLength(array(
                        'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
                        'min' => 2
                )));

                $validation->add('updatecompleted', new PresenceOf(array(

                        'field' => 'updatecompleted', 
                        'message' => 'Het veld updatecompleted is verplicht.'
                )));

                $validation->add('updatecompleted', new StringLength(array(
                        'messageMinimum' => 'Vul een updatecompleted in van tenminste 2 characters.',
                        'min' => 2
                )));


                $messages = $validation->validate($post);
                if (count($messages))
                {
                        $status['messages'] = $this->getmessages($messages);
                }
                else
                {
                        $entity = $this->getEntity('mgmtupdate',$post);

                        //save standard columns 
                        foreach ($entity as $key => $value)
                        {
                                if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
                                { 
                                        $entity->$key = $post[$key]; 
                                }				
                        }

                        if($this->new){ $entity->creationdate = date("H:i:s Y-m-d"); }
                         $entity->lastedit = date("H:i:s Y-m-d"); 
                         $entity->updatecompleted = date("H:i:s Y-m-d",strtotime($post["updatecompleted"])); 


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
                                                echo 'mgmtupdate:'.$message;
                                }
                        }		
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
                    $Mgmtupdate = Mgmtupdate::findFirst('id = "'.$post['id'].'"');			
                    if($Mgmtupdate->delete())
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

            $entity = Mgmtupdate::findFirst('id = "'.$id.'"');
            $this->view->setVar("entity", $entity);
            $this->view->setVar("entityid", $entity->id);

            $acl = Acl::findFirst();		
            $this->view->setVar("acl", $acl);

            $cm = $acl->columnMap();
            foreach($cm as $column)
            { $form->add(new Text($column, array('value' => $acl->$column,'id' => $column,'class' => 'form-control'))); }	

            $this->view->setVar("form", $form);
    }
}
?>
