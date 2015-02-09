	<? if(!isset($config['entityid'])){ $config['entityid'] = '573489573487587489'; } ?>
	<form id="dropzone" action="<?=$this->url->get('file/multipleupload');?>" class="dropzone">
		<input name="entityid" value="<?=$config['entityid'];?>" type="hidden" />
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
				$('.dropzone-trash').click(function(){
					fileid = $(this).attr('id');
					save({ data:{id:fileid},action:'<?=$this->url->get('file/deletefile');?>',goto:false});
					$('.'+fileid).hide();		
				});
			});
		
			this.on("sending",function(file,xhr,formData){ formData.append("entityid", '<?=$config['entityid'];?>'); });
			this.on("success",function(file,response){ 
				obj = JSON.parse(response);
				gridview();
			});
		   }	
		};
	</script>