<?php
namespace Phalcon\Forms;

use Phalcon\Mvc\Model\Resultset;

class MultipleFileUpload extends Element implements ElementInterface
{
	private $name;
	private $form;
	private $file;
	
	private $placeholder;
	private $x;
	private $y;
	private $url;
	
    public function  __construct($name, $options = false)
    {
		$this->name = $name;
		if(isset($options['id'])){ $this->id = $options['id']; }else{ $this->id = ''; }
		if(isset($options['url'])){ $this->url = $options['url']; }else{ $this->url = ''; }
		
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
		if(isset($options['x'])){ $this->x = $options['x']; }else{ $this->x = ''; }
		if(isset($options['y'])){ $this->y = $options['y']; }else{ $this->y = ''; }
		
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

	private function getHtml()
	{
		$html = '
				<form id="dropzone" action="'.$this->url.'file/multipleupload" class="dropzone">
					<div class="fallback">
						<input name="file" type="file" multiple />
					</div>
				</form>
				<script>
					Dropzone.options.dropzone = {
					  maxFilesize: 2, // MB
					  accept: function(file, done)
					  {
							
					  }
					};
					Dropzone.options.dropzone = {
					  init: function()
					  {
						/* DROPZONE.JS IS COMPLETELY CUSTOM DONT UPDATE OR YOU HAVE TO CUSTOMIZE AGAIN!!! */
						//	this.on("addedfile", function(file) { alert("Added file."); });
						this.on("complete", function(file) { 
							$(\'.dropzone-trash\').click(function(){
								fileid = $(this).attr(\'id\');
								save({ data:{id:fileid},action:\''.$this->url.'file/deletefile\',goto:false});
								$('.'+fileid).hide();		
							});
						});
						this.on("sending",function(file,xhr,formData){ formData.append("entityid", '.$this->id.'); });
						this.on("success",function(file,response){ 
							obj = JSON.parse(response);
							gridview();
						});
					   }	
					};
				</script>
				';			
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