function FileUploader(id,url,o)
{	
	var uploader = new plupload.Uploader(
	{
		runtimes : 'html5,flash,silverlight,html4',
		browse_button : 'pickfiles'+id, 
		container: document.getElementById('container'+id), 
		url : url+'file/add',
		flash_swf_url : '../js/Moxie.swf',
		silverlight_xap_url : '../js/Moxie.xap',
		filters : {
			max_file_size : '10mb',
			mime_types: [
				{title : "Image files", extensions : "jpeg,jpg,gif,png,svg"}
			]
		},
		init: 
		{
			PostInit: function()
			{
				detailarrayn = null;
				filearray = null;   
			},
			FilesAdded: function(up, files) 
			{
				plupload.each(files, function(file) {
	
				});
				 uploader.start();
				 $('.thumbnail').css('height',$('.thumbnail').height()+30);
			},
			UploadProgress: function(up, file) 
			{
				$('#uploadpercentage').show();
				$('#uploadpercentage').val(file.percent);
			},
			Error: function(up, err) 
			{
				alert("\nError #" + err.code + ": " + err.message); 
			}
		}
	});
	
	uploader.init();
	uploader.bind("BeforeUpload", function(up,file){
		uploader.settings.multipart_params = { resize: o.resize,screenheight:$(window).height(),screenwidth:$(window).width(),thumb:o.thumb };
	});
	
	uploader.bind('FileUploaded', function(upldr, file, object) 
	{
		
		$('.thumbnail').css('height',$('.thumbnail').height()-10);
		$('#uploadpercentage').hide();	
			
		json = jQuery.parseJSON( object.response );
		path = url+'x/'+json.path;
		
		$('#'+id).val(json.id);
		$('#pickfiles'+id).html('<img style="max-width:'+o.x+'px;max-height:'+o.y+'px;" id="picture" src="'+path+'" />');
		
		if(o.crop == true)
		{
			cropPicture(id,path)
		}
		else
		{ 
			//	$('.fototrash').show();
			//	$('.fototrash').attr('id',detailarrayn[detailarrayn.length-1].id);
		} 
	});
}

function cropPicture(id,path)
{
	$('.fototrash').show();	
	$('#myModal'+id).modal('show');
	
	$('.cropper'+id).attr("src",path);	

	
}

function cropimage(id,args)
{
	
}

