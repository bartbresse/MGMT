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

class DatabuilderController extends ControllerBase
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
	
    public function singleentityAction()
    {
        $this->view->disable();
        $post = $this->post;

        if(isset($post['id']) && isset($post['entity']))
        {
            $mgmtentity = MgmtEntity2::findFirst('id = "'.$post['entity'].'"');
           
            $entityname = ucfirst($mgmtentity->name);
            $entity = $entityname::findFirst('id = "'.$post['entityid'].'"');

            $columnmap = $entityname::columnMap();
            $mgmtcolumns = MgmtEntitycolumn::find('mgmtentityid = "'.$mgmtentity->id.'"');
            
            $html = '<table>';
            
            foreach($mgmtcolumns as $column)
            {
                $name = $columnmap[$column->name];    
                if(isset($name))
                {
                    if($column->show == 1 && $column->name != 'fileid' && $column->name != 'id')
                    {
                        $html .= '<tr><td>'.$column->alias.'</td>'
                            . '<td>'.$entity->$name.'</td></tr>';
                    }
                } 
            }
            
            $html .= '</table>';
            
            $this->status['entity'] = $mgmtentity->name;
            $this->status['table'] = $html;
            $this->status['status'] = 'ok';
        }
        //$this->singleentityformAction();
        echo json_encode($this->status);
    }
    
    public function entityselectionAction()
    {
        $this->view->disable();
        $post = $this->post;
        
        $entity = MgmtEntity2::find('id = "'.$post['entity'].'"');
        $entityname = ucfirst($entity->name);
        
        
        
        die();
    }
    
    /**
     * Selection form to  select tables
     */
    public function singleentityformAction()
    {
        $this->view->disable();
        $post = $this->request->getPost();
        
        $form = new Form();
        
        $form->add(new Select("type",array('Een rij','Meerdere rijen'),array("value" => '' ,"id" => "type","class" => "form-control3434 select")));
        $form->add(new Select("entity",MgmtEntity2::find() ,array("using" => array("id","name"),"value" => '',"id" => "entity","class" => "form-control3434 select")));
        
        //$form->add(new Select("planeid",Plane::find() ,array("using" => array("id","titel"),"value" => $Passagier->planeid ,"id" => "planeid","class" => "form-control3434 select")));

        $this->view->setVar("form", $form);
        $this->view->partial("databuilder/singleentityform"); 
    }
    
    
    /**
     * Handles a request for a single entity row
     */
    public function argumentselectionformAction()
    {
        $this->view->disable();
        $post = $this->request->getPost();
        $form = new Form();
        
        $entity = MgmtEntity2::findFirst('id = "'.$post['entity'].'"'); 
        $e = ucfirst($entity->name);
        
        $form->add(new Select("argument",$e::find(),array('using' => array('id','titel'),"value" => '' ,"id" => "argument","class" => "form-control3434 select")));
        
        $this->view->setVar("entity", $entity->id);
        $this->view->setVar("form", $form);
        $this->view->partial("databuilder/argumentselectionform"); 
    }
    
    /**
     * Handles a request for multiple entity rows
     */
    public function argumentsselectionformAction()
    {
        $this->view->disable();
        $post = $this->request->getPost();
        $form = new Form();
        
        $entity = MgmtEntity2::findFirst('id = "'.$post['entity'].'"'); 
        $e = ucfirst($entity->name);
        
        $selects = array();
        $relationnames = array();
        
        $relations = MgmtRelation::find('fromid = "'.$entity->id.'"');
        $this->view->setVar("relations",$relations);  
        foreach($relations as $relation)
        {
            $relationentity = MgmtEntity2::findFirst('id = "'.$relation->toid.'"');
            $name = ucfirst($relationentity->name);
            $results = $name::find();
            if(count($results) > 0)
            {
                $form->add(new Select($relationentity->name,$name::find(),array('using' => array('id','titel'),"id" => $relationentity->name,"class" => "form-control3434 autocomplete")));
                array_push($selects,$form->render($relationentity->name));
                array_push($relationnames,$relationentity->name);
            }
        }
        
        $this->view->setVar("entity", $entity->id);
        $this->view->setVar("relationnames",$relationnames);
        $this->view->setVar("relations", $selects);
        
        $form->add(new Text("argumentstextfield",array("id" => "argumentstextfield","class" => "form-control3434 autocomplete")));
        $this->view->setVar("form", $form);
        $this->view->partial("databuilder/multipleargumentselectionform"); 
    }
}
?>
