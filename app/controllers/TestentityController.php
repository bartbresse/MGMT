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

class TestentityController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('public');
        Phalcon\Tag::setTitle('Tests');
        parent::initialize();
        $this->setModule('public');
        $this->post = $this->request->getPost();
        $this->get =  $this->request->getQuery();
        $this->session = $this->session->get('auth');
        $this->entity = 'Testentity';

        $mgmtform = new MgmtUtils\MgmtForm();
        $this->view->setVar("mgmtform", $mgmtform);
    }
	
    public function indexAction()
    {			
        $this->view->setVar("mgmtform", new MgmtUtils\MgmtForm());
        $this->setaction($this->entity);
        $this->setcolumns($this->entity);
        $Testentitys = $this->search2(array('columns' => array_keys(Testentity::columnMap()),'entity' => $this->entity)); 
        if(count($Testentitys) > 0){ $this->view->setVar("Testentitys", $Testentitys); }
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

        $Testentitys = $this->search2(array('columns' => array_keys(Testentity::columnMap()),'entity' => 'testentity','args' => $args)); 
        if(count($Testentitys) > 0)
        { 
            $this->view->setVar("Testentitys", $Testentitys); 
        }		
        $this->view->partial("testentity/clean"); 	
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
            $args['entity'] = 'Testentity';
            $args['columns'] = array_keys(Testentity::columnMap());	

            $Testentitys = $this->search2($args);
            if(count($Testentitys) > 0)
            {
                    $this->view->setVar("Testentitys", $Testentitys); 
            }

            $this->view->setvar("post", $post);
            $this->view->partial("testentity/clean"); 	
        }
    }

    public function editAction()
    {
        $form = new Form();
        $post = $this->post;
        $entityid = $this->uuid();
        $id = $this->request->getQuery('id');

        $Testentity = Testentity::findFirst('id = "'.$id.'"');
        //files
        $files = $this->getfiles('Testentity',$Testentity->id);		
        $this->view->setVar("files", $files);	

        $cm = $Testentity->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('value' => $Testentity->$column,'id' => $column,'class' => 'form-control9092'))); }	
        //TODO add special fields

        $form->add(new TextEditor("beschrijving", array("id" => "beschrijving","value" => $Testentity->beschrijving,"class" => "form-control9092")));
                                $form->add(new Text("lastedit", array("value" => $Testentity->lastedit,"id" => "lastedit","class" => "form-control9092 datetime")));
                                $form->add(new Text("creationdate", array("value" => $Testentity->creationdate,"id" => "creationdate","class" => "form-control9092 datetime")));


        $this->view->setVar("Testentity", $Testentity);		
        $this->view->setVar("entityid", $entityid);
        $this->view->setVar("form", $form);
        $this->view->pick("testentity/new");	
    }

    public function newAction()
    {
        $form = new Form();
        $session = $this->session;
        $post = $this->post;		
        $entityid = $this->uuid();

        $Testentity = Testentity::findFirst();
        if(!isset($Testentity->id))
        {
                $Testentity = new Testentity();
                $Testentity->userid = $this->uuid(); 
        }

        $cm = $Testentity->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('id' => $column,'class' => 'form-control9092'))); }	

        $form->add(new TextEditor("beschrijving", array("id" => "beschrijving","value" => $Testentity->beschrijving,"class" => "form-control9092")));
        $form->add(new Text("lastedit", array("value" => $Testentity->lastedit,"id" => "lastedit","class" => "form-control9092 datetime")));
        $form->add(new Text("creationdate", array("value" => $Testentity->creationdate,"id" => "creationdate","class" => "form-control9092 datetime")));


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
            $validation = $this->striptagsfilter($validation,array("id","titel","beschrijving","lastedit","slug","tags"));


            $validation->add('titel', new PresenceOf(array(

                    'field' => 'titel', 
                    'message' => 'Het veld titel is verplicht.'
            )));

            $validation->add('titel', new StringLength(array(
                    'messageMinimum' => 'Vul een titel in van tenminste 2 characters.',
                    'min' => 2
            )));

            $validation->add('beschrijving', new PresenceOf(array(

                    'field' => 'beschrijving', 
                    'message' => 'Het veld beschrijving is verplicht.'
            )));

            $validation->add('beschrijving', new StringLength(array(
                    'messageMinimum' => 'Vul een beschrijving in van tenminste 2 characters.',
                    'min' => 2
            )));

            $validation->add('tags', new PresenceOf(array(

                    'field' => 'tags', 
                    'message' => 'Het veld tags is verplicht.'
            )));

            $validation->add('tags', new StringLength(array(
                    'messageMinimum' => 'Vul een tags in van tenminste 2 characters.',
                    'min' => 2
            )));


            $messages = $validation->validate($post);
            if (count($messages))
            {
                    $status['messages'] = $this->getmessages($messages);
            }
            else
            {
                $entity = $this->getEntity('testentity',$post);

                //save standard columns 
                foreach ($entity as $key => $value)
                {
                        if(isset($post[$key]) && $key != 'id' && strlen($post[$key]) > 0)
                        { 
                                $entity->$key = $post[$key]; 
                        }				
                }

                if($this->new){ $entity->authorid = $this->user["id"]; }
                 $entity->lastedit = date("H:i:s Y-m-d"); 
                 $entity->slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $post['titel'])); 
                if($this->new){ $entity->creationdate = date("H:i:s Y-m-d"); }


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
                        echo 'testentity:'.$message;
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
                $Testentity = Testentity::findFirst('id = "'.$post['id'].'"');			
                if($Testentity->delete())
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

        $entity = Testentity::findFirst('id = "'.$id.'"');
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
