<?php
	if(!isset($config['class'])){ $config['class'] = 'form-control'; }	
	if(!isset($config['id'])){ $config['id'] = 'beschrijving'; }
	if(!isset($config['content'])){ $config['content'] = ''; }	
?>

<script type="text/javascript">
$(document).ready(
	function()
	{
		$('#<?=$config['id'];?>').redactor({
			fileUpload: '../demo/scripts/file_upload.php'
		});
	}
);
</script>

<div id="page">
	<textarea id="<?=$config['id'];?>" class="<?=$config['class'];?>"  name="content" placeholder="Uw beschrijving...">
			<?=$config['content'];?>
	</textarea>
</div>
