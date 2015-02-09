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

class MailController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('email');
        Phalcon\Tag::setTitle('Email');
      	parent::initialize();
        $this->setModule('email');
        $this->entity = 'MgmtEmail';
    }

    public function indexAction()
    {			
        $this->setaction();
        $this->setcolumns();
        $entity = $this->search2(array('columns' => array_keys(MgmtEmail::columnMap()),'entity' => $this->entity)); 
        if(count($entity) > 0){ $this->view->setVar("emails", $entity); }
    }

    public function selectsenderAction()
    {
        $this->view->disable();
        $post = $this->request->getPost(); 
        $adresslist = array();
       
        $users = User::find();
        foreach($users as $user)
        {
            $adres = array('value' => $user->email.' '.$user->firstname.' '.$user->insertion.' '.$user->lastname);
            //array_push($);
        }
        
        $this->status['json'] = $adresslist;
        $this->status['status'] = 'ok';
        
        echo json_encode($this->status);
    }
    
    public function cleanAction()
    {
        $this->view->disable();
        $post = $this->request->getPost();

        $this->setaction();
        $this->setcolumns();

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

        $emails = $this->search2(array('columns' => array_keys(MgmtEmail::columnMap()),'entity' => $this->entity,'conditions' => $args)); 
        if(count($emails) > 0)
        { 
                $this->view->setVar("emails", $emails); 
        }		
        $this->view->partial("mail/clean"); 	
    }

    public function newAction()
    {
        $form = new Form();
        $session = $this->session;
        $post = $this->post;		
        $entityid = $this->uuid();

        $user = MgmtEmail::findFirst();
        if(!isset($user->id))
        {
            $user = new MgmtEmail();
            $user->userid = $this->uuid(); 
        }

        $cm = $user->columnMap();
        foreach($cm as $column)
        { $form->add(new Text($column, array('id' => $column,'class' => 'form-control'))); }	
        //TODO add special fields
        $form->add(new Select("documenttemplate",Documenttemplate::find() ,array("using" => array("id","titel"),"value" => $Passagier->planeid ,"id" => "planeid","class" => "form-control3434 select")));
        $form->add(new TextEditor("message", array("id" => "message","class" => "form-control ")));

        $this->view->setVar("entityid", $entityid);
        $this->view->setVar("form", $form);
    }
}
