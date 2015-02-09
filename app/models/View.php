<?php

class View extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
	}

	//translations
	public $language;
	// model name 
	public $entity;
	// model columns array
	public $columns;
	// all relating entities with columns
	public $relations;
	// to facilitate linktables
	public $linkrelations;
	// determines wether there is a multiple file upload function or not //true or false
	public $images;
	// requires a function  
	public $functions; 
	// tables with columns
	public $tablesobject;
	
	//baseuri 
	public $baseuri;

	private $filearray;
	
	private function columnlist()
	{
		$cols = array();
		$columns = $this->columns;		
		foreach($columns as $column)
		{
			array_push($cols,$column['Field']);
		}
		return $cols;
	}

	//new / edit methods
	private function getfields()
	{
		$str = '';
		$columns = $this->columns;

		$linkrelations = $this->linkrelations;
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
			if($rel[0] == $this->entity)
			{ $relation = $rel[1]; }
			else
			{ $relation = $rel[0]; }
						
			$tables = $this->tablesobject;
			$firstfields = array('titel','title','firstname');
			
			if(in_array('titel',$tables[$relation]))
			{
				//<?=$'.$relation.'->;>
				$text = 'Selecteer een titel...';
				$select = '<?=$'.$relation.'->titel;?>';
			}
			else if(in_array('firstname',$tables[$relation]))
			{
				$text = 'Selecteer een naam...';	
				$select = '<?=$'.$relation.'->firstname;?> <?=$'.$relation.'->insertion;?> <?=$'.$relation.'->lastname;?>';
			}
			else
			{
				$text = 'Select a title...';
				$select = '<?=$'.$relation.'->title;?>';
			}

		    $str .= '<div class="control-group" id="'.$relation.'group">							
						<label class="control-label" for="fileid"><?=$lang->translate("'.$relation.'");?>
							<ul id="'.$relation.'iderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="'.$relation.'s" class="'.$relation.'-multiple-select form-control"  data-placeholder="'.$text.'">
									<? foreach($'.$relation.'s as $'.$relation.'){ ?>
	<option <? if(isset($'.$rel[0].$rel[1].'s) && in_array($'.$relation.'->id,$'.$rel[0].$rel[1].'s)){ echo \'selected\'; } ?> value="<?=$'.$relation.'->id;?>">'.$select.'</option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".'.$relation.'-multiple-select").chosen({ width: \'100%\' });
						</script>
					</div>';				
		}
		
		//no columns velden die geen invulbaar veld horen te hebben
		$filecc = 0;
		$nocolumns = array('slug','creationdate','lastedit');
		foreach($columns as $column)
		{
			$languages = $this->language;
			$langkeys = array_keys($languages);

			if(in_array(strtolower($column['Field']),$langkeys))
			{ 
				$columntitle = ucfirst($languages[$column['Field']]); 
			}
			else
			{ 
				$columntitle = ucfirst($column['Field']); 
			}						

			$name = explode('id',$column['Field']);			
			if(!isset($name[1]) && $column['Type'] != 'varchar(36)' && $column['Type'] != 'text' && !in_array($column['Field'],$nocolumns) && !$column['Type'] != 'int'  && !$column['Type'] != 'int(1)')
			{
				if($column['Null'] != 'YES'){ $asterix = '*'; }else{ $asterix = ''; }
				
				//GIVE THIS INTERNATIONALLY RECOGNIZABLE NAME
				if($column['Field'] == 'locatiex')
				{
					$str .= '
							<script type="text/javascript" src=\'http://maps.google.com/maps/api/js?sensor=false&libraries=places\'></script>
							<script src="http://dev12-hetworks.nl/MGMT/backend/public/js/locationpicker.js"></script>			
							
							<div class="control-group" id="hoofdtitelgroup">
								<label class="control-label" for="hoofdtitel"><?=$lang->translate("Locatie");?>
									<ul id="hoofdtitelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><input type="text" id="us2-address" style="width: 200px"/></div>
								</div>
							</div>
							<div class="control-group" id="hoofdtitelgroup">
								<label class="control-label" for="hoofdtitel">
									<ul id="hoofdtitelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div id="us2" style="width: 400px; height: 350px;"></div>	
								</div>
							</div>
							<div class="control-group" id="hoofdtitelgroup">
								<label class="control-label" for="hoofdtitel">Coordinaten
									<ul id="hoofdtitelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
							Lat.: <input type="text" class="form-control" id="locatiex"/>	Long.: <input class="form-control" type="text" id="locatiey"/>
								</div>
							</div>
							<script>
								$(\'#us2\').locationpicker({
									location: {latitude: 52.132633, longitude: 5.2912659999999505},	
									enableAutocomplete: true,
									zoom:7,
									inputBinding: 
										{
											latitudeInput: $(\'#locatiex\'),
											longitudeInput: $(\'#locatiey\'),
											locationNameInput: $(\'#us2-address\') 
										}
									});
							</script> 
							';
				}
				else if($column['Field'] == 'locatiey')
				{
					
				}
				else if($column['Field'] == 'password')
				{
					$str .= '<div class="control-group" id="'.$column['Field'].'group">
								<label class="control-label" for="'.$column['Field'].'"><?=$lang->translate("'.$columntitle.'");?>'.$asterix.'
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("password"); ?></div>
								</div>
							</div>
							<div class="control-group" id="'.$column['Field'].'group">
								<label class="control-label" for="'.$column['Field'].'"><?=$lang->translate("'.$columntitle.'");?>'.$asterix.'
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("password2"); ?></div>
								</div>
							</div>
							';
				}
				else
				{
					$str .= '<div class="control-group" id="'.$column['Field'].'group">
								<label class="control-label" for="'.$column['Field'].'"><?=$lang->translate("'.$columntitle.'");?>'.$asterix.'
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("'.$column['Field'].'"); ?></div>
								</div>
							</div>
							';
				}
			}

			$nofields = array('id','userid','parentid');
			if($column['Null'] != 'YES'){ $asterix = '*'; }else{ $asterix = ''; }
			if($column['Type'] == 'int(1)')
			{
					$str .= '<div class="control-group" id="'.$column['Field'].'group">
								<label class="control-label" for="'.$column['Field'].'"><?=$lang->translate("'.$columntitle.'");?>'.$asterix.'
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
								<!--	<div class="col-sm-8"><?php echo $form->render("'.$column['Field'].'"); ?></div>-->
									<div id="gender" class="btn-group" data-toggle="buttons">
										<label class="btn btn-default" data-toggle-passive-class="btn-default" data-toggle-class="btn-primary">
											<input type="radio" value="male" class="form-control" id="'.$column['Field'].'" name="gender" data-parsley-multiple="gender" data-parsley-id="8481">
											On
										</label>
										<label class="btn btn-primary active" data-toggle-passive-class="btn-default" data-toggle-class="btn-primary">
											<input type="radio" value="female" class="form-control" id="'.$column['Field'].'" name="gender" data-parsley-multiple="gender" data-parsley-id="8481">
											Off
										</label>
									</div>
								</div>
							</div>
							';
			}
			else
			{
				if($column['Field'] == 'fileid')
				{
					$this->filearray = 'filearray:filearray,' ;
					$str .= '
							<div class="control-group" id="'.$column['Field'].$asterix.'group">							
								<label class="control-label" for="'.$column['Field'].'"><?=$lang->translate("Foto");?>
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls control-group">
									<div style="margin-left:15px;">
								<?	//crop images settings
									$config = array(\'slot\' => '.$filecc.',\'x\' => 210,\'y\' => 210,\'cx\' => 200,\'cy\' => 200,\'id\' => 23,\'crop\' => \'true\');	?>
								<?php $this->partial("file/singleupload"); ?> 	
									</div>							
								</div>
							</div>
							';				
					$filecc++;		
				}
				else if($column['Field'] == 'parentid' && $column['Type'] == 'varchar(36)')
				{
					//TODO: kan efficienter onder een gewone column
					$str .= '
							<div class="control-group" id="parentidgroup">							
								<label class="control-label" for="parentid"><?=$lang->translate("'.$column['Field'].$asterix.'");?>
									<ul id="parentiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("parentid"); ?></div>			
								</div>
							</div>
							';
				}
				else if(!in_array($column['Field'],$nofields) && isset($name[1]))
				{
				    //TODO MAKE FIELDS FOR UNIDENTIFIED SINGLE TO MANY RELATIONS
					$rel = explode('id',$column['Field']);				
   				    $str .= '<div class="control-group" id="'.$column['Field'].'group">							
								<label class="control-label" for="'.$column['Field'].'id"><?=$lang->translate("'.$rel[0].'");?>
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
									
										<select id="'.$column['Field'].'" class="'.$column['Field'].'-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($'.$rel[0].'s as $'.$rel[0].'){ ?>
												<option <? if(isset($'.$this->entity.'->id) && $'.$rel[0].'->id == $'.$this->entity.'->id){ echo \'selected\'; } ?> value="<?=$'.$rel[0].'->id;?>"><?=$'.$rel[0].'->titel;?></option>
											<? } ?>
										</select>
									
									</div>
								</div>
								<script type="text/javascript">
									 $(".'.$column['Field'].'-multiple-select").chosen({ width: \'100%\' });
								</script>
							</div>';
				}	
			}

			if($column['Type'] == 'text')
			{
				if($column['Null'] != 'YES'){ $asterix = '*'; }else{ $asterix = ''; }
				$str .= '	<div class="control-group" id="'.$column['Field'].'group">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("'.$columntitle.'");?>'.$asterix.'
									<ul id="'.$column['Field'].'error" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array(\'id\' => \''.$column['Field'].'\',\'class\' => \'form-control\');	
										if(isset($'.$this->entity.'->'.$column['Field'].')){ $config[\'content\'] = $'.$this->entity.'->'.$column['Field'].'; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						';
			}

			if($column['Type'] == 'date')
			{
				$str .= '<script>$(\'#'.$column['Field'].'\').datepicker();</script>
						';
			}
			
			if($column['Type'] == 'datetime')
			{
				$str .= '<script>$(\'#'.$column['Field'].'\').datetimepicker();</script>
						';
			}
		}
	
		if($this->images)
		{			
			$str .= '
					<div class="control-group" id="filesgroup">							
						<label class="control-label" for="fileid"><?=$lang->translate("Foto\'s");?>'.$asterix.'
							<ul id="fileserror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls control-group">
							<div>
							<?	//images settings
								$config = array(\'entity\' => \''.$this->entity.'\');	?>
							<?php $this->partial("file/multipleupload"); ?> 	
							</div>							
						</div>
					</div>
					';
		}
		return $str;
	}

	//detail actions
	private function detailfields()
	{
		$str = '';
		$columns = $this->columns;

		$nocolumns = array('password');

		foreach($columns as $column)
		{
			$name = explode('id',$column['Field']);			
			if(!isset($name[1]) && !in_array($column['Field'],$nocolumns))
			{
				$str .= "
						<tr>
						   <td><b><?=$lang->translate('".$column['Field']."');?></b></td>
						   <td><?php echo $".$this->entity."->".$column['Field']."; ?></td>	
						</tr>
						";
			}		
		}
		return $str;
	}

	//detail view functions
	private function detailfiles()
	{
		$str = '<img src="<?=$'.$this->entity.'->file->path; ?>" />';
		
		return $str;	
	}


	/* tabs in detail view of entity  */
	public function getrelationtabs()
	{
		$str = '';
		$relations = $this->relations;
		$cc=0;	
		foreach($relations as $relation)
		{
			if($cc == 0){ $class='active';}
			$str .= '<li class="'.$class.'">
						<a href="#'.$relation.'" data-toggle="tab"><?=$email->translate("'.$relation.'s");?></a>
					</li>
					';	
			$class='';
			$cc++;		
		}
		
		$linkrelations = $this->linkrelations;
		$cc=0;	
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
			if($rel[0] == $this->entity){ $relation = $rel[1]; }else{ $relation = $rel[0]; }
			if($cc == 0){ $class='active';}
			$str .= '<li class="'.$class.'">
						<a href="#'.$relation.'" data-toggle="tab">'.$relation.'s</a>
					</li>
					';	
			$class='';
			$cc++;		
		}		
		return $str;
	}

	public function getrelations()
	{
		$cc2=0;
		$str = '';
		$root = $_SERVER['DOCUMENT_ROOT'].$this->baseuri;
		$relations = $this->relations;
		$cc=0;	
		foreach($relations as $relation)
		{
			if($cc == 0){ $class='activex';$active = 'active';$cc2++; }		
			$str .= '
					<div id="'.$relation.'" class="tab-pane '.$active.'">
						<script>
							loadform({action:\'<?=$this->url->get(\''.$relation.'/clean\');?>\',id:\''.$relation.'-control\',data:{field:\''.$this->entity.'id\',value:\'<?=$_GET[\'id\'];?>\'}});
						</script>	
						<div id="'.$relation.'-control">
							loading form 
						</div>
					</div>
					';
					
			/*	
					<div id="bericht" class="tab-pane active">
						<script>
							loadform({action:'<?=$this->url->get('bericht/clean');?>',id:'category-control',data:{field:'categoryid',value:<?=$_GET['id'];?>}});
						</script>	
						<div id="category-control">
							loading form 
						</div>
					</div>
			*/	
					
			$class='';
			$cc++;
			$active = '';
		}
		
		$linkrelations = $this->linkrelations;
		$cc=0;	
		foreach($linkrelations as $relation)
		{
			$rel = explode('_',$relation);
			if($rel[0] == $this->entity){ $relation = $rel[1]; }else{ $relation = $rel[0]; }
			if($cc == 0){ $class='activex';if($cc2 == 0){ $active = 'active'; } $cc2++; }			

			$str .= '<div id="'.$relation.'" class="tab-pane '.$active.'">
						<?php if (url_exists(BASEURL.\'backend/'.$this->entity.'/index\')){ $this->partial("'.$relation.'/clean"); } ?>
				    </div>
					';
				
			$class='';
			$cc++;
			$cc2++;
			$active ='';
		}
		return $str;
	}

	public function getfunctions()
	{
		$str = ' ';
		$functions = $this->functions; 
		
		if(is_array($functions))
		{
			foreach($functions as $function)
			{
				$entity = explode('_',$function);	
				if($entity[2] == 'new')
				{
					$str .= '
							<!-- getfunctions start custom widget -->
							<div class="col-md-4">
								<section class="widget">		
									<div id="'.$entity[0].'-control">
										<?=$this->partial("file/'.$entity[3].'");?> 
									</div>
								</section>
							</div>
							<!-- end custom widget -->
							';
				}
			}
		}

		return $str;
	}

	public function getviewfunctions()
	{
		$str = '';
		$functions = $this->functions; 
		
		
		
		
		if(is_array($functions))
		{
			print_r($functions);
		
			foreach($functions as $function)
			{
				$entity = explode('_',$function);	
				
				if($entity[2] == 'view')
				{
		

				$str .= '
							<div class="row">
								<div class="col-md-10">
									<section class="widget">
										<script>
	/*		loadform({action:\'<?=$this->url->get(\''.$entity[0].'/template\')?>\',id:\''.$entity[0].'-control\',template:\''.$entity[3].'\',dataid:\''.$entity[1].'\'});*/
										</script>	
										<div id="'.$entity[0].'-control">
											<?=$this->partial("file/'.$entity[3].'");?> 
										</div>
									</section>
								</div>
							</div>
							';
				}
			}
		//	echo $this->entity;
		}
		return $str;
	}
	
	public function tofile()
	{
		//create folder
		$foldername = str_replace('_','',$this->entity);		
		if (!file_exists('../app/views/'.$foldername)) {
			mkdir('../app/views/'.$foldername, 0777, true);
		}

		//overview
		$contents =	file_get_contents(BASEURL.'backend/templates/index.rsi');
		$a = array('#entities#','#Entity#','#entity#');
		$b = array($this->entity.'s',ucfirst($this->entity),$this->entity);
		$model = str_replace($a,$b,$contents);
		$file = '../app/views/'.$foldername.'/index.volt';
		
		if(strlen($model) > 10)
		{ 
			unlink($file);
			
			file_put_contents($file,$model);
			//TODO rechten aanpassen is mogelijk security issue
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten

			//linux goes haywire because the file system is a little different
			if(!is_writable($file)){  }chmod($file,0777); 
		} 
		else
		{ 
			return false; 
		}
		
		/* clean table view without formatting*/
		//clean
		$contents =	file_get_contents(BASEURL.'backend/templates/clean.rsi');
		$a = array('#entities#','#Entity#','#entity#');
		$b = array($this->entity.'s',ucfirst($this->entity),$this->entity);
		$model = str_replace($a,$b,$contents);
		$file = '../app/views/'.$foldername.'/clean.volt';
		if(strlen($model) > 10)
		{ 
			unlink($file);
		
			file_put_contents($file,$model);
			//TODO rechten aanpassen is mogelijk security issue
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
			
			//linux goes haywire because the file system is a little different
			if(!is_writable($file)){ } chmod($file,0777); 
		}
		else
		{ 
			return false; 
		}
		
		//detail
		$contents =	file_get_contents(BASEURL.'backend/templates/view.rsi');
		$a = array('#entity#','#fields#','#files#','#entities#','#relationtabs#','#relations#','#functions#');
		$b = array($this->entity,$this->detailfields(),$this->detailfiles(),$this->entity.'s',$this->getrelationtabs(),$this->getrelations(),$this->getviewfunctions());
		$model = str_replace($a,$b,$contents);
		$file = '../app/views/'.$foldername.'/view.volt';
		if(strlen($model) > 10)
		{ 			
			unlink($file);
		
			file_put_contents($file,$model); 
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten
			
			//linux goes haywire because the file system is a little different
			if(!is_writable($file)){ }  chmod($file,0777);
		}
		else
		{ 
			return false; 	
		}

		//new/edit
		/*
		if(file_exists('../app/views/'.$this->entity.'/new.volt'))
		{
			$usercontents =	file_get_contents('../app/views/'.ucfirst($this->entity).'/new.volt');
			$left = explode('#startuserspecific#',$usercontents);	

			if(isset($left[1])){ $right = explode('#enduserspecific#',$left[1]); }
	
			$usercontent  = '#startuserspecific#';		
			if(isset($right[0])){ $usercontent .= $right[0]; }
			$usercontent .= '#enduserspecific#';		
		}
		else
		{
			$usercontent  = '#startuserspecific#

							 #enduserspecific#';	
		}*/
		
		$contents =	file_get_contents(BASEURL.'backend/templates/new.rsi');
		$a = array('#entities#','#Entity#','#entity#','#fields#','#files#','#filearray#','#functions#');
		$b = array($this->entity.'s',
					$this->entity,
					$this->entity, 
					$this->getfields(),
					$this->filearray,
					'',
					$this->getfunctions());
		$model = str_replace($a,$b,$contents);

		
		$file = '../app/views/'.$foldername.'/new.volt';
	
		if(strlen($model) > 10)
		{ 
			unlink($file);
		
			file_put_contents($file,$model); 
			//anders geen groep edit /np++ edit mogelijk wegens te weinig rechten

			//linux goes haywire because the file system is a little different
			if(!is_writable($file)){  }chmod($file,0777); 
		} 
		else
		{ 
			return false; 
		}		
		
		/*RELATION VIEWS FROM LINK TABLES*/
		
		/*
		$linkrelations = $this->linkrelations;
		$cc=0;	
		foreach($linkrelations as $relation)
		{
			$contents =	file_get_contents(BASEURL.'backend/templates/relation.rsi');
			$a = array('#entities#','#Entity#','#entity#');
			$b = array($this->entity.'s',ucfirst($this->entity),$this->entity);
			$model = str_replace($a,$b,$contents);
			$file = '../app/views/'.$foldername.'/relation.volt';
			if(strlen($model) > 10){ file_put_contents($file,$model); 
		}*/
		return true;
	}  

    public function columnMap(){ return array(); }
}
