<?php
namespace MgmtEntity;

class MgmtViewView extends MgmtFile
{
	public function __construct($entity)
	{
		$this->path = '../app/views/'.strtolower($entity->name).'/view.volt';
		parent::__construct($entity);
	}	
	

        private function getAlias($line)
	{
		if(strlen($line->alias) > 0)
		{
			return ucfirst($line->alias);
		}
		else
		{
			return ucfirst($line->name);
		}
	}
        
	private function getFields()
	{
            $fields = '';

            $lines = $this->entity->lines;
            $relations = $this->entity->relations;

            $columns = $this->entity->getVisibleColumns();

            foreach($lines as $line)
            {
                    if($line->show == 1)
                    {
                        $field = '
                                <div class="control-group" id="'.$line->name.'group">
                                    <label class="control-label" for="'.$line->name.'">'.$this->getAlias($line).'
                                        <ul id="'.$line->name.'error" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                        <div><?php echo $entity->'.$line->name.'; ?></div>
                                    </div>
                                </div>';
                        $fields .= $field;
                    }
            }
                
            /*
            foreach($relations as $relation)
            {
                    $field = '
                            <div class="control-group" id="'.$relation->toname.'group">
                                    <label class="control-label" for="'.$relation->toname.'">'.ucfirst($relation->toname).'
                                            <ul id="'.$relation->toname.'error" class="parsley-errors-list"></ul>
                                    </label>
                                    <div class="controls form-group">
                                            <div><?=$'.$entity.'->?></div>
                                    </div>
                            </div>
                            ';
                    $fields .= $field;		
            }*/

            return $fields;
	}
	
	private function getFunctions()
	{
		return '//functions';
	}
	
	public function toFile()
	{
		$contents =	file_get_contents(BASEURL.'templates/view2.rsi');
		// #Entity# #fields##files##relationtabs##relations##functions#
		$a = array('#entity#','#Entity#','#fields#','#functions#');
		$b = array($this->entity->name,ucfirst($this->entity->name),$this->getFields(),$this->getFunctions());
		
		$this->create($a,$b,$contents,$this->path);		
	}
}


?>