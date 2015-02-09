<?php

class Modell extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		
	}
	//model name 
	public $name;
	//model columns array
	public $columns;
	//model relations
	
	// list of database tables
	public $tables;	
	// object that contains all tables and columns
	public $tablesobject;
	
	public function getstandards()
	{
		$str = '';
		$columns = $this->columns;
		$cc = 0;		
	
		$str .= 'public function afterFetch()
				 { ';
		
		foreach($columns as $column)
		{
			switch(strtolower($column['Type']))
			{
				case 'datetime':
					$str .= '


					';
					break;
			}
		}

		$str .= '}';

		$str .= 'public function beforeSave()
		 { ';
		
		foreach($columns as $column)
		{
			switch($column['Field'])
			{
				case 'slug':
					if(in_array($columns,$column))
					$str .= '
						$'.$this->name.'->slug = preg_replace("/[^a-zA-Z]/", "", $post[\'titel\']);
					';					
				break;
				case 'lastedit':
					$str .= '
						$'.$this->name.'->lastedit = new Phalcon\Db\RawValue(\'now()\');				
					';				
				break;
			}

			switch(strtolower($column['Type']))
			{
				case 'datetime':
					$str .= '

						$eudate = date("d-m-y H:i:s",strtotime($this->'.$column['Field'].'));
						$this->'.$column['Field'].' = $eudate;

					';
					break;
			}
		}

		$str .= '}';
		//$str .= $astr;

		/*
			public function beforeSave()
			{
				//Convert the array into a string
				$this->status = join(',', $this->status);
			}
		*/
		return $str;
	}
	
	public function initializetostring()
	{
		$str = '';
		$columns = $this->columns;
		$cc = 0;		
		
		//TODO HAS MANY RELATIONSHIPS
		$tablesobject = $this->tablesobject;
		
		$tables = $this->tables;
		foreach($tables as $table)
		{
		//	echo $table.'
		//				';
						
						
		
			foreach($tablesobject[$table] as $column)
			{
				if($column == $this->name."id")
				{
					$exptable = explode('_',$table);
					if(isset($exptable[1]))
					{	$table = ucfirst($exptable[0]).ucfirst($exptable[1]); }
					else
					{	$table = ucfirst($exptable[0]);	}
					
					$str .= ' $this->hasMany("id", "'.$table.'", "'.$this->name.'id"); 
							';
					// echo $this->name.'>> $this->hasMany("id", "'.$table.'", "'.$this->name.'id"); ';
				}
			}
		}
		
		foreach($columns as $column)
		{
			//if the column name contains id it is a relationship
			$name = explode('id',$column['Field']);			
			if(isset($name[1]))
			{
				$str .= ' $this->hasOne("'.strtolower($name[0]).'id", "'.ucfirst($name[0]).'", "id"); 
						';
			}
			
			//if the column is id it needs a default value
			if(strtolower($column['Field']) == 'id')
			{
				$str .= 'if(strlen($this->'.strtolower($column['Field']).') < 8)
						 { 
							$this->'.strtolower($column['Field']).' =  new Phalcon\Db\RawValue(\'uuid()\');
						 }';
			}
			
			//if the column is datetime it needs a default value
			if(strtolower($column['Type']) == 'datetime')
			{
				$str .= 'if(strlen($this->'.strtolower($column['Field']).') < 8 || $this->'.strtolower($column['Field']).' == \'0000-00-00\')
						 { 
							$this->'.strtolower($column['Field']).' =  new Phalcon\Db\RawValue(\'now()\');
						 }';
			}
			
		}
		return $str;
	}

	public function getname()
	{
		$exn = explode('_',$this->name);
		if(isset($exn[1]))
		{
			return ucfirst($exn[0]).ucfirst($exn[1]);
		}
		else
		{
			return ucfirst ($this->name);
		}
	}

	public function columnstostring()
	{
		$str = '';
		$columns = $this->columns;
		foreach($columns as $column)
		{
		
		$str .= '/*
				  *
				  *
				  * @var '.$column['Type'].'
				  */
				  public $'.strtolower($column['Field']).';
				
				';
		}
		return $str;
	}

	public function columnmaptostring()
	{
		$str = '';
		$columns = $this->columns;
		$cc = 0;		
		foreach($columns as $column)
		{
			if($cc != 0){$str.=',';}
			$str .= "'".$column['Field']."' => '".$column['Field']."'";$cc++;
		}
		return $str;
	}

	public function getvalidation()
	{
		$str = 'public function validation()
				{';
		$columns = $this->columns;
		$cc = 0;		
		
		foreach($columns as $column)
		{
			if($column['Field'] == 'titel' || $column['Field'] == 'slug')
			{
				$str .= '$this->validate(new Uniqueness(array(
							  \'field\' => \''.$column['Field'].'\',
							  \'message\' => \'Dit '.$column['Field'].' is al een keer gebruikt\'
							)));
						';
			}
			else if($column['Key'] == 'PRI' || $column['Key'] == 'UNI')
			{
				$str .= '$this->validate(new Uniqueness(array(
							  \'field\' => \''.$column['Field'].'\',
							  \'message\' => \'Deze '.$column['Field'].' is al een keer gebruikt\'
							)));
						';
			} 
			
			
			$cc++;
		}

		if($cc > 0){
		$str .= '
					
					return $this->getMessages();

				}';
		}
		else
		{
			$str .= '
					
					return array();

				}';
			return array();
		}
		return $str; 
	}

	public function getstandardfunctions()
	{
		$str = '	public function afterFetch()
    {';
		$columns = $this->columns;
		
			
		foreach($columns as $column)
		{
			if($column['Type'] == 'datetime')
			{
				$str .= '$this->'.$column['Field'].' = date(\'H:i:s d-m-Y\',strtotime($this->'.$column['Field'].'));
';
			}
		}

		$str .= '}';
		return $str;
	}	

	//create a model based on current columns & relations
	public function tofile()
	{
		$contents =	file_get_contents(BASEURL.'backend/templates/model.rsi');
		$a = array('#name#','#initialize#','#columns#','#columnmap#','#standards#','#validation#','#standardfunctions#');
		$b = array($this->getname(),
					$this->initializetostring(),
					$this->columnstostring(),
					$this->columnmaptostring(),
					$this->getstandards(),
					$this->getvalidation(),
					$this->getstandardfunctions());

		$model = str_replace($a,$b,$contents);
		$file = '../app/models/'.$this->getname().'.php';

		if(!is_writable($file)){  }chmod($file,0777); 

		if(strlen($model) > 10){ file_put_contents($file,$model);
		
		//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten

		//linux goes haywire because the file system is a little different (ubuntu 12.04 LTS)
			if(!is_writable($file))
			{  
			
			}
			
			chmod($file,0777); 
		
		} else{ return false; }
		return true;
	}  
 
    public function columnMap(){ return array(); }
}
