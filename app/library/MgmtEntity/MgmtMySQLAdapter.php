<?php
namespace MgmtEntity;

class MgmtMySQLAdapter
{
	private $db;
	
	public function __construct()
	{
//		$this->connect($config);
	}
	
	public function connect($config)
	{
		$this->db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
			"host" => $config->database->host,
			"username" => $config->database->username,
			"password" => $config->database->password,
			"dbname" => $config->database->name
		));
	}
	
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
	
	public function updateTable($entity)
	{
		$lines = $entity->lines;
		if(count($lines) > 0)
		{
			//get current state
			$originalentity  = $this->getTable($entity);
			
			foreach($lines as $line)
			{
				
			}
		}
	}
	
	private function getTable($entity)
	{
		$columns = $this->db->fetchAll("SHOW FULL COLUMNS FROM `".strtolower($entity->name)."`", Phalcon\Db::FETCH_ASSOC);
		foreach($columns as $column)
		{
			print_r($column);			
		}
		return $entity;
	}
	
	public function deleteTable()
	{
	
	}


}
?>