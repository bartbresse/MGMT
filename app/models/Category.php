<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Category extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
		 $this->hasMany("id", "Bericht", "categoryid"); 
							 $this->hasMany("id", "Event", "categoryid"); 
							 $this->hasMany("id", "Pagina", "categoryid"); 
							 $this->hasMany("id", "Workshop", "categoryid"); 
							 $this->hasOne("id", "", "id"); 
						if(strlen($this->id) < 8)
						 { 
							$this->id =  new Phalcon\Db\RawValue('uuid()');
						 } $this->hasOne("parentid", "Parent", "id"); 
						 $this->hasOne("userid", "User", "id"); 
						 $this->hasOne("fileid", "File", "id"); 
						if(strlen($this->lastedit) < 8 || $this->lastedit == '0000-00-00')
						 { 
							$this->lastedit =  new Phalcon\Db\RawValue('now()');
						 }if(strlen($this->creationdate) < 8 || $this->creationdate == '0000-00-00')
						 { 
							$this->creationdate =  new Phalcon\Db\RawValue('now()');
						 } $this->hasOne("paginaid", "Pagina", "id"); 
						
						
						
						
								
	
	}

		public function afterFetch()
    {$this->lastedit = date('H:i:s d-m-Y',strtotime($this->lastedit));
$this->creationdate = date('H:i:s d-m-Y',strtotime($this->creationdate));
}

	/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $id;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $titel;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $slug;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $beschrijving1;
				
				/*
				  *
				  *
				  * @var text
				  */
				  public $beschrijving2;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $niveau;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $parentid;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $userid;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $fileid;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $hoofdtitel;
				
				/*
				  *
				  *
				  * @var varchar(255)
				  */
				  public $header;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $lastedit;
				
				/*
				  *
				  *
				  * @var datetime
				  */
				  public $creationdate;
				
				/*
				  *
				  *
				  * @var varchar(36)
				  */
				  public $paginaid;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $locatiex;
				
				/*
				  *
				  *
				  * @var int(11)
				  */
				  public $locatiey;
				
				public $locatie;
	

	public function validation()
	{
		$this->validate(new Uniqueness(array(
			  'field' => 'id',
			  'message' => 'Deze id is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'titel',
			  'message' => 'Dit titel is al een keer gebruikt'
			)));
		$this->validate(new Uniqueness(array(
			  'field' => 'slug',
			  'message' => 'Dit slug is al een keer gebruikt'
			)));

		return $this->getMessages();
	}
 
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
			'id' => 'id','titel' => 'titel','slug' => 'slug','beschrijving1' => 'beschrijving1','beschrijving2' => 'beschrijving2','niveau' => 'niveau','parentid' => 'parentid','userid' => 'userid','fileid' => 'fileid','hoofdtitel' => 'hoofdtitel','header' => 'header','lastedit' => 'lastedit','creationdate' => 'creationdate','paginaid' => 'paginaid','locatiex' => 'locatiex','locatiey' => 'locatiey','locatie' => 'locatie'
        );
    }
	
	public function genereerBreadcrumb($url,$pagetype = false,$pagetype_titel = false,$pagetitel = false)
	{	
		/*
		<div id="breadcrumbs">
			<li><a href="<?=$this->url->get();?>">Home</a></li>
			<? if($niveau == 1 && isset($category) && isset($category->titel)) { ?><li><a href="<?=$this->url->get($p);?>"><?= $category->titel; ?></a></li><?php } ?>
			<li><a href="<? if($niveau == 1 && isset($category) && isset($category->titel)) { echo $this->url->get('bericht/?p='.$p); } else { echo $this->url->get('bericht/'); } ?>">Berichten</a></li>
			<li><a class="current-page"><?=$bericht->titel;?></a></li>
		</div>
		*/
		
		$html = '<div id="breadcrumbs">
					<li><a href="'.$url.'">Home</a></li>';
		$parenturl = '';
		//plaats
		if($this->niveau == 2)
		{
			$parenturl = '/?p='.$url.$this->slug.'/';
			$html .= '<li><a href="'.$url.$this->slug.'/">'.$this->titel.'</a></li>';
			
		}
		
		//team
		if($this->niveau == 3) 
		{ 
			$parent = $this::findFirst('id = "'.$this->parentid.'"');
			
			$parenturl = $url.'/?p='.$parent->slug.'/'.$this->slug.'/';
			
			$html .= '<li><a href="'.$url.$parent->slug.'/">'.$parent->titel.'</a></li>';
			$html .= '<li><a href="'.$url.$parent->slug.'/'.$this->slug.'/">'.$this->titel.'</a></li>';
		} 
		
		if($pagetype)
		{
			if($this->niveau > 1) {
				$html .= '<li><a href="'.$url.$pagetype.'/?p=/'.$this->slug.'/">'.$pagetype_titel.'</a></li>';
			}
			else {
				$html .= '<li><a href="'.$url.$pagetype.'/">'.$pagetype_titel.'</a></li>';
			}
		}
		
		if($pagetitel)
		{
			$html .= '<li><a class="current-page">'.$pagetitel.'</a></li>';
		}		
					
		$html .= '</ul></div>';	
		
		return $html;
	}
}
