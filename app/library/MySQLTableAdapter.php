<?php

class MySQLTableAdapter extends Phalcon\Mvc\User\Component
{	
	public function createTable($entity)
	{
		$lines = $entity->lines;
		if(count($lines) > 0)
		{
			$cc=0;
			$sql = 'CREATE TABLE IF NOT EXISTS `'.strtolower($entity->name).'` (';	
			foreach($lines as $line)
			{
				if($cc>0){$sql.=',';}
				$sql .= '`'.$line->name.'` '.$line->getTypeLength().' '.$line->getNull().' '.$line->getSQLComment();	
				$cc++;
			}
			
			//add keys 
			$uniqueColumns = array();
			foreach($lines as $line)
			{
				if($line->unique == 1)
				{
					array_push($uniqueColumns,$line->name);
				}
			}
			
			$sql .= ',PRIMARY KEY (`id`)';
			
			if(count($uniqueColumns))
			{
				$sql .= ', UNIQUE KEY `'.$uniqueColumns[0].'` (';
				$cc=0;
				foreach($uniqueColumns as $column)
				{
					if($cc>0){$sql.=',';}
					$sql .= '`'.$column.'`';
					$cc++;
				}
				$sql .= ')';
			}
			
			$sql .= ') ENGINE=InnoDB DEFAULT CHARSET=latin1'.$entity->getComment().';';
			return $sql;
		}
	}
	
	public function updateTable($originalentity,$entity)
	{
		$originalcolumns = array();
		$lines = $entity->lines;
		
		$linelist = $originalentity->listLines();
		
		if(count($lines) > 0)
		{
			$sql = array();
			foreach($lines as $line)
			{	
				if($originalentity->findLine($line->name))
				{
					/*
					echo 'here
					
					';*/
				
					array_push($originalcolumns,$line->name);
					if($line->null == 1)
					{
						$null = ' NULL ';
					}
					else
					{
						$null = ' NOT NULL ';
					}

					//CHARACTER SET latin1 COLLATE latin1_swedish_ci
					$column = 'ALTER TABLE `'.strtolower($entity->name).'` CHANGE `'.$line->name.'` `'.$line->name.'` '.strtoupper($line->type).'  '.$null;
					array_push($sql,$column);
				}
				else
				{
					/*
					echo 'here2
					
					';*/
					if($line->null == 1)
					{
						$null = ' NULL ';
					}
					else
					{
						$null = ' NOT NULL ';
					}
					
					$column = 'ALTER TABLE `'.strtolower($entity->name).'` ADD `'.$line->name.'` '.strtoupper($line->type).' '.$null;
					array_push($sql,$column);
				}
			}
			
			//DELETES IN entity/edit FORM DELETED COLUMNS
			foreach($linelist as $linel)
			{
				if(!$entity->findLine($linel))
				{
					$column = 'ALTER TABLE `'.strtolower($entity->name).'` DROP `'.$linel.'`';
					array_push($sql,$column);
				}
			}

			return $sql;
		}
	}
	
	public function getTable($entity)
	{
		$columns = $this->db->fetchAll("SHOW FULL COLUMNS FROM `".strtolower($entity->name)."`", Phalcon\Db::FETCH_ASSOC);
		
		$newentity = new MgmtEntity\MgmtEntity($entity->name);
		
		foreach($columns as $column)
		{
			$entityline = new MgmtEntity\MgmtEntityLine();
			$entityline->name = $column['Field'];
			$entityline->type = $column['Type'];
			$entityline->default = $column['Default'];
			
			if($column['Null'] == 'NO')
			{
				$entityline->null = 0;
			}
			else
			{
				$entityline->null = 1;
			}
			
			$entityline->comments = $column['Comment'];
			$entityline->show = 0;
			
			$newentity->addLine($entityline);
		}
		return $newentity;
	}
	
	public function deleteTable()
	{
	
	}


}
?>