<?php
namespace Phalcon\Forms;

use Phalcon\Mvc\Model\Resultset;

class FileUpload extends Element implements ElementInterface
{
	private $name;
	private $form;
	private $file;
	private $crop;
	
	private $placeholder;
	private $x;
	private $y;
	
	private $html;
	
	private $selectfromlibrary;
	
    public function  __construct($name, $options = false)
    {
		$this->name = $name;
		if(isset($options['id'])){ $this->id = $options['id']; }else{ $this->id = ''; }
		if(isset($options['class'])){ $this->class = $options['class']; }else{ $this->class = '';}
		if(isset($options['value']))
		{ 
			$this->file = $options['value']; 
			$this->value = $options['value'];
		}
		else
		{ 
			$this->value = '';
			$this->file = '';
		}
		
		if(isset($options['placeholder'])){ $this->placeholder = $options['placeholder']; }else{ $this->placeholder = ''; }
		if(isset($options['x'])){ $this->x = $options['x']; }else{ $this->x = 200; }
		if(isset($options['y'])){ $this->y = $options['y']; }else{ $this->y = 200; }
		if(isset($options['selectfromlibrary'])){ $this->selectfromlibrary = $options['selectfromlibrary']; }else{ $this->selectfromlibrary = false; }
		if(isset($options['crop'])){ $this->crop = $options['crop']; }else{ $this->crop = false; }
		
		return 'FileUPload';
    }
	
	public function setName ($name)
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setLibrary($images)
	{
			
		//fileUploadModal	
		//<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>
	/*
		$html = '
			<div class="modal fade bs-example-modal-lg" tabindex="-1" id="fileUploadModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
				  images
				</div>
			  </div>
			</div>
		';*/
	
	//	$this->html = $html;

		
	}
	
	private function getHtml()
	{
		
		/*<script src="'.BASEURL.'js/langs/nl.js"></script>*/
	
            
                if(isset($this->file->path)){ $path = $this->file->path; }else{$path = '';}
            
		$html = '   <div>
                                <script src="'.BASEURL.'js/plupload/plupload.full.min.js"></script>

                                <input type="hidden" id="'.$this->id.'" value="'.$path.'" class="'.$this->class.'" />

                                <div id="container'.$this->id.'">
                                        <span style="display:none;" id="placeholder'.$this->id.'">
                                            <img id="place" data-src="holder.js/'.$this->x.'x'.$this->y.'/text:Plaatje" alt="picture">
                                        </span>
                                        <progress style="display:none;width: 100%;margin-top:2px;" value="" max="100" id="uploadpercentage"></progress>
			';
		
	$html .= '<div class="thumbnail thumbnail'.$this->id.' has-error">';
				
	//echo $this->value;
				
	if(isset($this->file->id))
	{ 	
            $html .= '<span style="margin-top:10px;position:absolute;top:5px;left:0px;z-index:100;cursor:pointer;" onclick="deletepicture();" class="fa fa-trash-o fa-2x fototrash'.$this->id.'"></span>';
	} 
        
        $html .= '<a id="pickfiles'.$this->id.'" href="javascript:;" style="float:left;">';
     
        //min-height:'.$this->y.'px;max-height:'.$this->y.'px;max-width:'.($this->x+10).'px;
	if(isset($this->file->id))
	{ 
            //724 x 483
            
		$html .= '<img id="currentphoto" style="" src="'.$this->file->getThumb('backendpreview').'" />
				  <img style="display:none;" id="placeholder'.$this->id.'" data-src="holder.js/'.$this->x.'x'.$this->y.'/text:Plaatje" alt="picture">
				  <input type="hidden" value="'.$this->file->path.'" id="dbid'.$this->id.'"/>'; 
	}
	else
	{
		$html .= '<img id="place'.$this->id.'" data-src="holder.js/'.$this->x.'x'.$this->y.'/text:Plaatje" alt="picture">';
	} 
	
		$html .= '	</a>
						<progress style="display:none;width: 100%;margin-top:2px;" value="" max="100" id="uploadpercentage'.$this->id.'"></progress>
					</div>
			</div></div>';	
		if($this->crop == true){ $crop = 'true';}else{$crop = 'false';}	
			
			
		$html .= '
			<div class="modal fade" id="myModal'.$this->id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-dialog'.$this->id.'">
				<div class="modal-content modal-content'.$this->id.'">
				  <div class="modal-header modal-header'.$this->id.'">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title modal-title'.$this->id.'" id="myModalLabel"></h4>
				  </div>
				  <div class="modal-body modal-body'.$this->id.'">
						<div class="container-fluid">
							<div class="row">
							  <div class="col-md-12">.col-md-8</div>
							</div>
							<div class="row">
							  <div class="col-md-12"><img class="cropper'.$this->id.'" src=""/></div>
							</div>
						</div>				  
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Niet bijsnijden</button>
					<button type="button" class="btn btn-primary" onclick="saveupload'.$this->id.'();">Opslaan</button>
				  </div>
				</div>
			  </div>
			</div>
			<div class="clear"></div>
			<script src="'.BASEURL.'js/fileupload.js"></script>
			<script>
				id = \''.$this->id.'\';
				url = \'/MGMT/backend/\';
				o = {resize:true,thumb:false,crop:'.$crop.'}
				
				FileUploader(id,url,o);
			</script>
		';
		
		$html .= $this->html;
		
			
		return $html;
	}
	
    public function render($attributes = false)
	{
		return $this->getHtml();
	}
	
	public function getForm ()
	{
		return $this->form;
	}
	
	public function setForm ($form)
	{
		$this->form = $form;
	}
	
	public function getValue ()
	{
		return 'value';
	}
	
	public function setDefault ($value)
	{
		
	}

	public function getLabel()
	{
		return 'label';
	}
	
}

?>