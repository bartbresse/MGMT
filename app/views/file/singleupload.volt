<?
	if(!isset($config['delete'])){ $config['delete'] = true; }
	if(!isset($config['crop']))  { $config['crop'] = 'true'; }
	if(!isset($config['index'])) { $config['index'] = 0; 	 }
	if(!isset($config['thumb'])) { $config['thumb'] = 'false'; }
	if(!isset($config['slot'])) { $config['slot'] = rand(); }
	if(!isset($config['aspectratio'])) { $config['aspectratio'] = 1; }	
	//show crop screen not bigger than browser window
	if(!isset($config['cropwithin'])) { $config['cropwithin'] = false; }	
?>
<div>
{{ javascript_include('js/plupload/plupload.full.min.js') }}
{{ javascript_include('js/langs/nl.js') }}
<input type="hidden" name="x" value="" class="form-control<?=$config['id'];?>" id="x<?=$config['id'];?>"/>
<input type="hidden" name="y" value="" class="form-control<?=$config['id'];?>" id="y<?=$config['id'];?>"/>
<input type="hidden" name="w" value="" class="form-control<?=$config['id'];?>" id="w<?=$config['id'];?>"/>
<input type="hidden" name="h" value="" class="form-control<?=$config['id'];?>" id="h<?=$config['id'];?>"/>
<input type="hidden" name="path" value="" class="form-control<?=$config['id'];?>" id="path<?=$config['id'];?>"/>
<input type="hidden" name="path" value="" class="form-control<?=$config['id'];?>" id="path"/>
<div id="container<?=$config['id'];?>">
	<span style="display:none;" id="placeholder<?=$config['id'];?>">
		<img id="place" data-src="holder.js/<?=$config['x'];?>x<?=$config['y'];?>/text:Plaatje" alt="picture">
	</span>
	<script>
		function deletepicture()
		{
			$('#currentphoto').hide();
		
			id = $('#dbid<?=$config['id'];?>').val();
			$.ajax({
				url: '<?php echo  $this->url->get("file/deletepicture"); ?>',
				type: 'POST',
				dataType: 'json',
				data: { id:id },
				async:false,
				success: function(data)
				{ } 
			});
		
			$('.form-control<?=$config['id'];?>').each(function(i, obj) {
				$(this).val("");
			});
			$('.fototrash23').hide();
			return false;
		}
	</script>
	<div class="row">
		<div class="thumbnail thumbnail<?=$config['id'];?> has-error" style="height:<?=$config['y']+10;?>px;width:<?=$config['x']+10;?>px;">
			<?php   
				if(isset($files[$config['index']]) && isset($files[$config['index']]->path))
				{ ?>
			<span style="margin-top:10px;position:absolute;top:5px;left:0px;z-index:100;cursor:pointer;" onclick="deletepicture();" class="fa fa-trash-o fa-2x fototrash<?=$config['id'];?>"></span>
			<?	} ?>
			<a id="pickfiles<?=$config['id'];?>" href="javascript:;" style="float:left;">
				<?php   
				if(isset($files[$config['index']]) && isset($files[$config['index']]->path))
				{ ?>
	<img id="currentphoto" style="min-height:<?=$config['y'];?>px;max-height:<?=$config['y'];?>px;max-width:<?=$config['x']+10;?>px;" src="<?php echo $files[$config['index']]->getbackendpath(); ?>" />
	<img style="display:none;" id="placeholder<?=$config['id'];?>" data-src="holder.js/<?=$config['x'];?>x<?=$config['y'];?>/text:Plaatje" alt="picture">
	<input type="hidden" value="<?=$files[$config['index']]->id;?>" id="dbid<?=$config['id'];?>"/><? 
				}
				else
				{ ?>
				<img id="place<?=$config['id'];?>" data-src="holder.js/<?=$config['x'];?>x<?=$config['y'];?>/text:Plaatje" alt="picture">
			 <? } ?>
			</a>
			<progress style="display:none;width: 100%;margin-top:2px;" value="" max="100" id="uploadpercentage<?=$config['id'];?>"></progress>
		</div>
	</div>
</div>
<script type="text/javascript">
filearray<?=$config['id'];?> = [];
detailarray<?=$config['id'];?>n = [];

newwidth = 0;
newheight = 0;

oldheight = 0;
oldwidth = 0;

function factor()
{
	heightpercentage = ((newheight * 100) / oldheight); 
	widthpercentage = ((newwidth * 100) / oldwidth);
	alert(heightpercentage+'<>'+widthpercentage); 
}

function preparecrop<?=$config['id'];?>(h,w)
{

	$('.fototrash<?=$config['id'];?>').show();
	$('.fototrash<?=$config['id'];?>').attr('id',detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].id);
	$('#pickfiles<?=$config['id'];?>').html('<img style="min-width:<?=$config['x'];?>px;max-width:<?=$config['x'];?>px;max-height:<?=$config['y'];?>px;" id="picture<?=$config['id'];?>" src="<?=$this->url->getBaseUri(); ?>'+detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].path+'" />');


		<? if(preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])){  }else{ ?>
		
		$(".modal-body<?=$config['id'];?>").html('<img id="img<?=$config['id'];?>" width=""  src="<?=$this->url->getBaseUri(); ?>'+detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].path+'" />');
		$('.jcrop-keymgr').each(function(){
				$(this).hide();
		});
		width = 0;
		
		var imgURL = '<?=$this->url->getBaseUri(); ?>'+path;
		
		var img = $('<img src="'+imgURL+'"/>').load(function(){
			$('.modal-dialog<?=$config['id'];?>').width(this.width+35);
		});
		
		var imgwidth = w; // width of the image
		var imgheight = h; // height of the image
		
		oldheight = imgheight;
		oldwidth = imgwidth;

		$('#img<?=$config['id'];?>').Jcrop({
			aspectRatio: <?=$config['aspectratio'];?>,
			onSelect: updateCoords<?=$config['id'];?>,
			boxWidth:1000,
			setSelect: [0, 160, 160, 0],
			minSize:	[100,100],
			bgOpacity:   .4
		},function(){ jcrop_api1 = this; }); 
		
		$('#path<?=$config['id'];?>').val(detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].path);	
		
		$('#myModal<?=$config['id'];?>').modal('show');

		$('#img<?=$config['id'];?>').on('load', function() {
			// do whatever you want
			var img = document.getElementById('img<?=$config['id'];?>'); 
			//or however you get a handle to the IMG
			var width = img.clientWidth;//this return 0 sometimes
			var height = img.clientHeight;
			img.clientWidth = $(this).width();
			w = $(this).width();
			
			if(w > 1000)
			{
				var w = window.innerWidth-400;
				if(w > 1000)
				{
					w = 1000;
				}
			}

			newwidth = w;			
			h = $('.jcrop-holder img').height();
			newheight = h;
		
				$('.modal-dialog<?=$config['id'];?>').css('width',w+40);
			
			$('.jcrop-keymgr').each(function(){
				$(this).hide();
			});
		});

		$('.modal-dialog').css('width',(w+40));
		$('.jcrop-keymgr').css('display','none');
	
	<? } ?>
}

function updateCoords<?=$config['id'];?>(c) 
{
	$("#x<?=$config['id'];?>").val(c.x);
	$("#y<?=$config['id'];?>").val(c.y);
	$("#w<?=$config['id'];?>").val(c.w);
	$("#h<?=$config['id'];?>").val(c.h);
}

function saveupload<?=$config['id'];?>()
{
	$('#myModal<?=$config['id'];?>').modal('hide');
		var data = {};
	
		$(".form-control<?=$config['id'];?>").each(function(){
			id = $(this).attr('id');
			val = $(this).val();	
			data[id] = val; 
		});
		
		//ALTIJD JSON GETALLEN AFRONDEN ANDERS WORD HIJ NIET GOED GECROPT
		data['x<?=$config['id'];?>'] = Math.floor(data['x<?=$config['id'];?>']);
		data['y<?=$config['id'];?>'] = Math.floor(data['y<?=$config['id'];?>']);
		data['w<?=$config['id'];?>'] = Math.floor(data['w<?=$config['id'];?>']);
		data['h<?=$config['id'];?>'] = Math.floor(data['h<?=$config['id'];?>']); 
		
		
		data['path'] = $('#path<?=$config['id'];?>').val();
		data['files'] = filearray<?=$config['id'];?>;
		data['num'] = <?=$config['id'];?>;
		data['thumb'] = '<?=$config['thumb'];?>';
		//data['id'] = $('')
		
	$.ajax({
		url: '<?php echo  $this->url->get("file/addcrop"); ?>',
		type: 'POST',
		dataType: 'json',
		data: data,
		async:false,
		success: function(data)
		{ 
			if(data.status == 'ok')
      		{     
				cachebreaker = '?' + new Date().getTime();
				$('#picture<?=$config['id'];?>').attr("src", '<?=$this->url->getBaseUri(); ?>'+data.path+cachebreaker);
			} 
		} 
	});	
}

var uploader<?=$config['id'];?> = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles<?=$config['id'];?>', 
	container: document.getElementById('container<?=$config['id'];?>'), 
	url : '<?php echo  $this->url->get('file/add'); ?>',
	flash_swf_url : '../js/Moxie.swf',
	silverlight_xap_url : '../js/Moxie.xap',
	filters : {
		max_file_size : '10mb',
		mime_types: [
			{title : "Image files", extensions : "jpeg,jpg,gif,png,svg"}
		]
	},
	init: {
		PostInit: function()
		{
			detailarray<?=$config['id'];?>n = null;
			filearray<?=$config['id'];?> = null;   
		},
		FilesAdded: function(up, files) 
		{
			plupload.each(files, function(file) 
			{
				detailarray<?=$config['id'];?>n = null;
				filearray<?=$config['id'];?> = null;   
				
				detailarray<?=$config['id'];?>n = [];
				filearray<?=$config['id'];?> = [];   
			});
			 uploader<?=$config['id'];?>.start();
			 $('.thumbnail<?=$config['id'];?>').css('height',$('.thumbnail<?=$config['id'];?>').height()+30);
		},
		UploadProgress: function(up, file) 
		{
			$('#uploadpercentage<?=$config['id'];?>').show();
			$('#uploadpercentage<?=$config['id'];?>').val(file.percent);
		},
		Error: function(up, err) 
		{
			alert("\nError #" + err.code + ": " + err.message); 
		}
	}
});

<? if(!isset($config['resize'])){ $config['resize'] = false; } ?>
<? if(!isset($config['crop'])){ $config['crop'] = true; } ?>

uploader<?=$config['id'];?>.init();
uploader<?=$config['id'];?>.bind("BeforeUpload", function(up,file){
uploader<?=$config['id'];?>.settings.multipart_params = { resize: '<? if($config['resize'] == 'false'){ echo 'false'; }else{ echo 'true'; } ?>',screenheight:$(window).height(),screenwidth:$(window).width(),thumb:'<?=$config['thumb']?>' };

});
uploader<?=$config['id'];?>.bind('FileUploaded', function(upldr, file, object) 
{
	$('.thumbnail<?=$config['id'];?>').css('height',$('.thumbnail<?=$config['id'];?>').height()-10);
	$('#uploadpercentage<?=$config['id'];?>').hide();	
		
	json = jQuery.parseJSON( object.response );

	detailarray<?=$config['id'];?>n.push(json);

	filearray[<?=$config['slot'];?>] = json.id;
	
	try 
	{ 
		<? if($config['crop'] != 'false')
		   { ?>
			preparecrop<?=$config['id'];?>(json.h,json.w);
		<? }
		   else
		   { ?>
				$('.fototrash<?=$config['id'];?>').show();
				$('.fototrash<?=$config['id'];?>').attr('id',detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].id);
				$('#pickfiles<?=$config['id'];?>').html('<img style="max-width:<?=$config['x'];?>px;max-height:<?=$config['y'];?>px;" id="picture<?=$config['id'];?>" src="<?=$this->url->getBaseUri(); ?>'+detailarray<?=$config['id'];?>n[detailarray<?=$config['id'];?>n.length-1].path+'" />');
			//	$('.thumbnail').css('height',$('#pickfiles<?=$config['id'];?> img').height());
		<? } ?>
	}
	catch(err) 
	{
		alert("\nError #" + err.code + ": " + err.message); 
	}
	filearray<?=$config['id'];?>.push(json.id);   
});

</script>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal<?=$config['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog<?=$config['id'];?>">
    <div class="modal-content modal-content<?=$config['id'];?>">
      <div class="modal-header modal-header<?=$config['id'];?>">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title modal-title<?=$config['id'];?>" id="myModalLabel"></h4>
      </div>
      <div class="modal-body modal-body<?=$config['id'];?>">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Niet bijsnijden</button>
        <button type="button" class="btn btn-primary" onclick="saveupload<?=$config['id'];?>();">Opslaan</button>
      </div>
    </div>
  </div>
</div>
<div class="clear"></div>