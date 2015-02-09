<?php
namespace MgmtEntity;

class MgmtModel extends MgmtFile
{
	public $columns;

	public function __construct($entity)
	{
		$this->path = '../app/models/'.ucfirst($entity->name).'.php';
		parent::__construct($entity);
	}
	
	private function createRelations()
	{
		$relations = $this->entity->relations;
		$html = '';
		
		foreach($relations as $relation)
		{
                    switch($relation->relationtype)
                    {
                        case 'HasMany':
                                $html .= '$this->hasMany("'.$relation->toname.'id", "'.ucfirst($relation->toname).'", "id");
                                ';
                        break;
                        case 'HasOne':
                                $html .= '$this->hasOne("'.$relation->toname.'id", "'.ucfirst($relation->toname).'", "id");
                                ';
                        break;			
                        case 'HasManyToMany':
                                $html .= '$this->hasManyToMany("'.$relation->toname.'id", "'.ucfirst($relation->toname).'", "id");
                                ';
                        break;
                    }
		}
		return $html;
	}
	
	private function createColumns()
	{
		$str = '';
		$columns = $this->entity->lines;
		if(count($columns))
		{
			foreach($columns as $column)
			{
				if(strtolower($column->name) != 'id')
				{
					if($column->null == 1)
					{
						$nullable = 'true';
					}
					else
					{
						$nullable = 'false';
					}
					
					if($column->length > 0)
					{
						$length = ', length='.$column->length;
					}else{ $length = ''; }
					
					$str .= '
							/**
							 * @Column(type="'.$column->type.'"'.$length.', nullable='.$column->null.')
							 */	
							  public $'.strtolower($column->name).';
							
							';
				}
			}
		}
		return $str;
	}
	
	private function createColumnMap()
	{
		$html = '';$cc=0;
		$lines = $this->entity->lines;
		foreach($lines as $line)
		{
			if($cc > 0){ $html .= ','; }
			$html .= '"'.$line->name.'" => "'.$line->name.'"';
			$cc++;
		}
		$html .= '';
		
		return $html;
	}
	
	private function createValidation()
	{
		
		$html = '';$cc=0;
		$lines = $this->entity->lines;
		foreach($lines as $line)
		{
			if($line->unique == 1)
			{
				$html .= '
					
                                            $this->validate(new Uniqueness(
                                                    array(
                                                            "field"   => "'.$line->name.'",
                                                            "message" => "Het veld '.$line->name.' moet uniek zijn."
                                                    )
                                              ));


                                             ';
				$cc++;
			}
		}
		$html .= '';
		return $html;
	}
	
	public function deleteFile()
	{
		$file = '../app/models/'.ucfirst($this->entity->name).'.php';
		$this->deleteFile($file);
	}
	
	public function toFile()
	{
		$contents =	file_get_contents(BASEURL.'templates/model2.rsi');
		$a = array('#name#','#initialize#','#columns#','#validation#','#columnmap#');
		$b = array(ucfirst($this->entity->name),$this->createRelations(),$this->createColumns(),$this->createValidation(),$this->createColumnMap());

		$this->create($a,$b,$contents,$this->path);		
	}
}

?>