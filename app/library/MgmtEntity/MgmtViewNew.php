<?php
namespace MgmtEntity;

class MgmtViewNew extends MgmtFile
{
	public function __construct($entity)
	{
		$this->path = '../app/views/'.strtolower(ucfirst($entity->name)).'/new.volt';
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
                                                            <div><?php echo $form->render("'.$line->name.'"); ?></div>
                                                    </div>
						</div>
						';
				$fields .= $field;
			}
		}
		
		foreach($relations as $relation)
		{
			$field = '
					<div class="control-group" id="'.$relation->toname.'group">
						<label class="control-label" for="'.$relation->toname.'">'.ucfirst($relation->toname).'
							<ul id="'.$relation->toname.'error" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div><?php echo $form->render("'.$relation->toname.'id"); ?></div>
						</div>
					</div>
					';
			$fields .= $field;		
		}
		
		return $fields;
	}
	
	private function getFunctions()
	{
		
	}

	public function toFile()
	{
		$contents =	file_get_contents(BASEURL.'templates/new2.rsi');
		$a = array('#Entity#','#class#','#entity#','#fields#','#functions#','#newtekst#');
		$b = array(ucfirst($this->entity->name),$this->entity->class,strtolower($this->entity->name),$this->getFields(),$this->getFunctions(),$this->entity->newtext);
		$model = str_replace($a,$b,$contents);
		$this->create($a,$b,$contents,$this->path);		
	}
}


?>