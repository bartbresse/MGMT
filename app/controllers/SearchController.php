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

class SearchController extends ControllerBase
{
    public function initialize()
    {
       // $this->view->setTemplateAfter('document');
        Phalcon\Tag::setTitle('Document');
        parent::initialize();
        $this->setModule('document');
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
    
    private function searchquery($entity,$search)
    {
       $cc=0;$q='';
       $columns = MgmtEntitycolumn::find('mgmtentityid = "'.$entity->id.'"');
       foreach($columns as $column)
       {
           if($cc>0){ $q .= ' OR '; }
           $q .= $column->name.' LIKE "%'.$search.'%" ';
           $cc++;
       }
       return $q;
    }
    
    private function formatResults($rows,$columnmap,$entityname)
    {
        $html = '';
        if(count($rows)>0)
        {
            $html .= $entityname.'<br />';
            $html .= '<table>';
            $cc=0;
            foreach($rows as $row)
            {
                if($cc==0)
                {
                    $html .= '<tr>';
                    foreach($columnmap as $map)
                    {
                        $rule = explode('id',$map);
                        if(!isset($rule[1]) && $map != 'slug')
                        {
                            $html .= '<th>'.$map.'</th>'; 
                        }
                    }   
                    $html .= '</tr>';
                }
                
                $html .= '<tr>';
                foreach($columnmap as $map)
                {
                   $rule = explode('id',$map);
                   if(!isset($rule[1]) && $map != 'slug')
                   {
                       $html .= '<td>'.$row->$map.'</td>'; 
                   }
                } 
                $html .= '</tr>';
                $cc++;
            }
            $html .= '</table>';
        }
        return $html;
    }
    
    public function mocksearchAction()
    {
        $this->view->disable();
        $post = $this->post;
        
        //get entities 
        $entities = array();
        
        $html = '';
        $cc=0;
        $mgmtentities = MgmtEntity2::find();
        foreach($mgmtentities as $mgmtentity)
        {
            $entityname = ucfirst($mgmtentity->name);
            
         //   echo $this->searchquery($mgmtentity,$post['search']).' 
               //     ';
            $entities = $entityname::find($this->searchquery($mgmtentity,$post['search']));
            
            $columnmap= $entityname::columnMap();
        
            if(count($entities) > 0)
            {
                $html .= $this->formatResults($entities,$columnmap,$entityname);
                $cc++;
            }
        }
        
        if($cc==0)
        {
            $html .= '<span>Geen resultaten gevonden.</span>';
        }
        
        $status['html'] = $html;
        $status['status'] = 'ok';
        
        echo json_encode($status);
    }
}
?>
