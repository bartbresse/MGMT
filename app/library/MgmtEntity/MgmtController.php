<?php
namespace MgmtEntity;

class MgmtController extends MgmtFile
{
	public function __construct($entity)
	{
            $this->path = '../app/controllers/'.ucfirst($entity->name).'Controller.php';
            parent::__construct($entity);
	}	
	
	private function getFilters()
	{
		$cc=0;$php='';
		$lines = $this->entity->lines;
		foreach($lines as $line)
		{
                    if($line->null == 0)
                    {
                        if($cc>0){$php .= ',';}
                        $php .= '"'.$line->name.'"';
                        $cc++;	
                    }	
		}
		return $php;
	}
	
	private function getValidation()
	{
		$php = '';
		$lines = $this->entity->lines;
		foreach($lines as $line)
		{
			if($line->null == 0 && $line->show == 1)
			{
				$php .= '
					$validation->add(\''.$line->name.'\', new PresenceOf(array(
						
						\'field\' => \''.$line->name.'\', 
						\'message\' => \'Het veld '.$line->name.' is verplicht.\'
					)));

					$validation->add(\''.$line->name.'\', new StringLength(array(
						\'messageMinimum\' => \'Vul een '.$line->name.' in van tenminste 2 characters.\',
						\'min\' => 2
					)));
				';
			}	
		}
                
                $relations = $this->entity->relations;
		foreach($relations as $relation)
		{
                    if($relation->null == 0)
                    {
                        switch($relation->relationtype)
                        {
                            case 'HasOne':
                                    $php .= '
                                        $validation->add(\''.$relation->toname.'id\', new PresenceOf(array(

                                                \'field\' => \''.$relation->toname.'id\', 
                                                \'message\' => \'Het veld '.$relation->toname.' is verplicht.\'
                                        )));

                                        $validation->add(\''.$relation->toname.'id\', new StringLength(array(
                                                \'messageMinimum\' => \'Selecteer een '.$relation->toname.'.\',
                                                \'min\' => 2
                                        )));
                                    ';
                                    break;
                            case 'HasMany':

                                    break;
                            case 'ManyToMany':

                                    break;	
                        }		    
                    }
		}
                
		return $php;
	}
	
	private function getFormFields()
	{
		$fields = '';
		$lines = $this->entity->lines;
		foreach($lines as $line)
		{	
			switch($line->phalcontype)
			{
				case 'TextArea':
					$fields .= '$form->add(new Textarea("'.$line->name.'", array("id" => "'.$line->name.'","value" => "'.ucfirst($this->entity->name).'->'.$line->name.'","class" => "'.$this->entity->class.'")));
					';
					break;
				case 'TextEditor':
					$fields .= '$form->add(new TextEditor("'.$line->name.'", array("id" => "'.$line->name.'","value" => $'.ucfirst($this->entity->name).'->'.$line->name.',"class" => "'.$this->entity->class.'")));
					';
					break;
				case 'FileUpload':
					$fields .= '$form->add(new FileUpload("'.$line->name.'",array("id" => "'.$line->name.'","value" => File::findFirst(\'id = "\'.$'.ucfirst($this->entity->name).'->'.$line->name.'.\'"\'),"class" => "'.$this->entity->class.'","x" => 200,"y" => 200,"crop" => false)));
					';
					break;	
				case 'Location':
					$fields .= '';
					break;
				case 'Password':
					$fields .= '$form->add(new Password("'.$line->name.'", array("value" => $'.ucfirst($this->entity->name).'->'.$line->name.',"id" => "'.$line->name.'","class" => "'.$this->entity->class.'")));
					';
					break;
				case 'id':
					$name = explode('id',$line->name);
					$fields .= '$form->add(new Select("'.$line->name.'",'.ucfirst($name).'::find() ,array("using" => array("id","titel"),"value" => $'.ucfirst($this->entity->name).'->'.$line->name.',"id" => "'.$line->name.'","class" => "'.$this->entity->class.'")));
					';
					break;
			}
			
			switch($line->type)
			{
				case 'DATETIME':
					$fields .= '$form->add(new Text("'.$line->name.'", array("value" => $'.ucfirst($this->entity->name).'->'.$line->name.',"id" => "'.$line->name.'","class" => "'.$this->entity->class.' datetime")));
					';
				break;
				case 'DATE':
					$fields .= '$form->add(new Text("'.$line->name.'", array("value" => $'.ucfirst($this->entity->name).'->'.$line->name.',"id" => "'.$line->name.'","class" => "'.$this->entity->class.' date")));
					';
				break;
			}
		}
		
		$relations = $this->entity->relations;
		foreach($relations as $relation)
		{
			switch($relation->relationtype)
			{
				case 'HasOne':
					$fields .= '$form->add(new Select("'.$relation->toname.'id",'.ucfirst($relation->toname).'::find() ,array("using" => array("id","titel"),"value" => $'.ucfirst($this->entity->name).'->'.$relation->toname.'id ,"id" => "'.$relation->toname.'id","class" => "'.$this->entity->class.' select")));
					';
					break;
				case 'HasMany':
					$fields .= '$form->add(new Select("'.$relation->toname.'id",'.ucfirst($relation->toname).'::find() ,array("using" => array("id","titel"),"value" => $'.ucfirst($this->entity->name).'->'.$relation->toname.'id ,"multiple" => "multiple","data-placeholder" => "Selecteer een '.$relation->toname.'","id" => "'.$relation->toname.'id","class" => "'.$this->entity->class.' select")));
					';
					break;
				case 'ManyToMany':
					$fields .= '$form->add(new Select("'.$relation->toname.'id",'.ucfirst($relation->toname).'::find() ,array("using" => array("id","titel"),"value" => $'.ucfirst($this->entity->name).'->'.$relation->toname.'id ,"multiple" => "multiple","data-placeholder" => "Selecteer een '.$relation->toname.'","id" => "'.$relation->toname.'id","class" => "'.$this->entity->class.' select")));
					';  
					break;	
			}		
		}
		
		return $fields;
	}
	
	public function getAddColumns()
	{
		$fields = '';
		$lines = $this->entity->lines;
		$columns = $this->entity->getVisibleColumns();
		$php = '';
                $linename = false;
                
		foreach($lines as $line)
		{	
                    switch($line->name)
                    {
                        case 'authorid':
                                $php .= 'if($this->new){ $entity->authorid = $this->user["id"]; }
                                ';
                                $linename = true;
                                break;
                        case 'creationdate':
                                $php .= 'if($this->new){ $entity->creationdate = date("H:i:s Y-m-d"); }
                                ';
                                $linename = true;
                                break;
                        case 'lastedit':
                                $php .= ' $entity->lastedit = date("H:i:s Y-m-d"); 
                                ';
                                $linename = true;
                                break;
                        case 'slug':
                                $php .= ' $entity->slug = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $post[\'titel\'])); 
                                ';
                                $linename = true;
                                break;
                    }

                    if(!$linename)
                    {
                        switch($line->type)
                        {
                            case 'DATETIME':
                                    $php .= ' $entity->'.$line->name.' = date("H:i:s Y-m-d",strtotime($post["'.$line->name.'"])); 
                                    ';
                                    break;
                            case 'DATE':
                                    $php .= ' $entity->'.$line->name.' = date("Y-m-d",strtotime($post["'.$line->name.'"]));
                                    ';
                                    break;
                        }
                    }
                    $linename = false;
		}
		return $php;
	}
	
	public function toFile($filename = 'backendcontroller2.rsi')
	{
            $contents =	file_get_contents(BASEURL.'templates/'.$filename);
            $a = array('#alias#','#class#','#Entity#','#entity#','#formfields#','#alias#','#template#','#validation#','#filters#','#addcolumns#');
            $b = array(	ucfirst($this->entity->alias),
                                $this->entity->class,
                                ucfirst($this->entity->name),
                                strtolower($this->entity->name),
                                $this->getFormFields(),
                                ucfirst($this->entity->alias),
                                strtolower($this->entity->module),
                                $this->getValidation(),
                                $this->getFilters(),
                                $this->getAddColumns());			
            $this->create($a,$b,$contents,$this->path);		
	}
}


?>