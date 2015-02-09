<?php
	if(!isset($config['class'])){ $config['class'] = 'form-control'; }	
	if(!isset($config['id'])){ $config['id'] = 'beschrijving'; }
	if(!isset($config['content'])){ $config['content'] = 'Uw beschrijving...'; }	
	if(!isset($config['contentid'])){ $config['contentid'] = 'Uw beschrijving...'; }		
?>

<script type="text/javascript">
$(document).ready(
	function()
	{
		$('#<?=$config['id'];?>-en').redactor({
			fileUpload: '../demo/scripts/file_upload.php'
		});
	}
);
$(document).ready(
	function()
	{
		$('#<?=$config['id'];?>-fr').redactor({
			fileUpload: '../demo/scripts/file_upload.php'
		});
	}
);
$(document).ready(
	function()
	{
		$('#<?=$config['id'];?>-de').redactor({
			fileUpload: '../demo/scripts/file_upload.php'
		});
	}
);
$(document).ready(
	function()
	{
		$('#<?=$config['id'];?>-nl').redactor({
			fileUpload: '../demo/scripts/file_upload.php'
		});
	}
);
</script>


<div class="tabbable tabs-below">
	<div id="myTabContentbottom" class="tab-content" style="padding:0px;">
		<div id="home-bottom" class="tab-pane fade active in">
			<div id="page"  style="border-top:0.5px solid #ccc;border-left:0.5px solid #ccc;border-right:0.5px solid #ccc;">
				<textarea id="<?=$config['id'];?>-nl"  name="content">
						<?=$config['content'];?>
				</textarea>
			</div>
		</div>
		<div id="profile-bottom" class="tab-pane fade" style="padding:0px;">
			<div id="page"  style="border-top:0.5px solid #ccc;border-left:0.5px solid #ccc;border-right:0.5px solid #ccc;">
				<textarea id="<?=$config['id'];?>-en"  name="content">
						<?=$config['content'];?>
				</textarea>
			</div>
		</div>
		<div id="dropdown3-bottom" class="tab-pane fade" style="padding:0px;">
			<div id="page"  style="border-top:0.5px solid #ccc;border-left:0.5px solid #ccc;border-right:0.5px solid #ccc;">
				<textarea id="<?=$config['id'];?>-de"  name="content">
						<?=$config['content'];?>
				</textarea>
			</div>			
		</div>
		<div id="dropdown4-bottom" class="tab-pane fade" style="padding:0px;">
			<div id="page"  style="border-top:0.5px solid #ccc;border-left:0.5px solid #ccc;border-right:0.5px solid #ccc;">
				<textarea id="<?=$config['id'];?>-fr"  name="content">
						<?=$config['content'];?>
				</textarea>
			</div>			
		</div>
	</div>
	<ul id="myTabbottom" class="nav nav-tabs ">
		<li class="active">
			<a data-toggle="tab" href="#home-bottom">NL</a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#profile-bottom">EN</a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#dropdown3-bottom">DU</a>
		</li>
		<li class="">
			<a data-toggle="tab" href="#dropdown4-bottom">FR</a>
		</li>
	</ul>
</div>

<input type="hidden" class="form-control" id="<?=$config['id'];?>" val="" />  




























