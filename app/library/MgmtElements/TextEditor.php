<?php
namespace Phalcon\Forms;

class TextEditor extends Element implements ElementInterface
{
	private $name;
	private $form;
	private $value;
	private $id;
	private $class;

        public function  __construct($name, $options = false)
        {
            $this->name = $name;
            if(isset($options['id'])){ $this->id = $options['id']; }else{ $this->id = ''; }
            if(isset($options['class'])){ $this->class = $options['class']; }else{ $this->class = '';}
            if(isset($options['value'])){ $this->value = $options['value']; }else{ $this->value = '';}
            if(isset($options['placeholder'])){ $this->placeholder = $options['placeholder']; }else{ $this->placeholder = ''; }
            return 'textEditor';
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
		/*
					<script type='text/javascript'>
				$(document).ready(
					function()
					{
						$('#".$this->id."').redactor({
							fileUpload: '../demo/scripts/file_upload.php',
						});
					}
				);
			</script>
			<div id='page' style='border:1px solid #ccc;'>
				<textarea id='".$this->id."' class='".$this->class."' name='content' placeholder='".$this->placeholder."'>".$this->value."</textarea>
			</div>
			
		*/
	

	$html = "

			
			<link href=\"".BASEURL."css/froala_editor.min.css\" rel=\"stylesheet\" type=\"text/css\">
			<link href=\"".BASEURL."css/froala_style.min.css\" rel=\"stylesheet\" type=\"text/css\">
			<section id=\"editor\">
			  <textarea class='".$this->class."' id='".$this->id."' style=\"margin-top: 30px;\" > ".$this->value." </textarea>	
			</section>
			<script src=\"".BASEURL."js/froala_editor.min.js\"></script>
			<!--[if lt IE 9]>
			<script src=\"".BASEURL."js/froala_editor_ie8.min.js\"></script>
			<![endif]-->
			<script src=\"".BASEURL."js/plugins/tables.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/lists.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/colors.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/font_family.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/font_size.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/block_styles.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/media_manager.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/video.min.js\"></script>
			<script src=\"".BASEURL."js/plugins/char_counter.min.js\"></script>

			<script>
			  $(function(){
				$('#".$this->id."').editable({inlineMode: false}) 
			  });
			</script>
				
				
				
			";
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