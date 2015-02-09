<script>
	o = {x:0,y:0,w:0,h:0,path:''};

	fileobject = {id:'',path:'',type:''};
</script>

<link href="<?=$this->url->get('css/cropper.css');?>" type="text/css" rel="stylesheet">
<script src="<?=$this->url->get('js/cropper.js');?>"></script>

<div class="modal fade bs-example-modal-lg" id="croppingmodal" style="display:none;" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		<div style="padding:10px;" id="croppicture"> </div>
			<div class="pull-right" style="padding:10px;">
				<button type="button" id="croppicturesave" class="btn btn-primary">Opslaan</button>	
			</div>
		<br /><br />
		<br />
    </div>
  </div>
</div>
<script>
	function cropimage(path,args)
	{
		o.path = path;
		$('#croppingmodal').modal('show');	
		$('#croppicture').prepend('<img src="'+path+'" id="croppable" />');
		//AMENSE THE CROPPENING
		amenseTheCropping();
		//THE CROPPENING IS AMENSING
	}
	
	$('#croppicturesave').click(function(){
		string = "datax:"+o.x+",datay:"+o.y+",dataheight:"+o.h+"datawidth"+o.w;
		$.ajax({
			url: '<?php echo  $this->url->get("file/crop"); ?>',
			type: 'POST',
			dataType: 'json',
			data: o,
			async:false,
			success: function(data)
			{ 
				if(data.status == "ok")
				{
					fileobject = data;

					$('#croppingmodal').modal('hide');	
					$('#croppicture').html(' ');
					
					
					notify();
				}
			} 
		});	
	});
	
	function amenseTheCropping() 
	{
		  var $image = $("#croppable"),
			  $dataX = $("#dataX"),
			  $dataY = $("#dataY"),
			  $dataHeight = $("#dataHeight"),
			  $dataWidth = $("#dataWidth"),
			  console = window.console || {log:$.noop},
			  cropper;

		  $image.cropper({
				aspectRatio: 4 / 1,
				// autoCropArea: 1,
				data: {
				  x: 420,
				  y: 50,
				  width: 640,
				  height: 360
				},
				preview: ".preview",

				// multiple: true,
				// autoCrop: false,
				// dragCrop: false,
				// dashed: false,
				// modal: false,
				// movable: false,
				// resizable: false,
				// zoomable: false,
				// rotatable: false,
				// checkImageOrigin: false,

				// maxWidth: 480,
				// maxHeight: 270,
				// minWidth: 160,
				// minHeight: 90,

				done: function(data)  
				{  
				  o.x = data.x;
				  o.y = data.y;
				  
				  o.h = data.height;
				  o.w = data.width;
				},

				build: function(e) {
				 // console.log(e.type);
				},

				built: function(e) {
				//  console.log(e.type);
				},

				dragstart: function(e) {
				//  console.log(e.type);
				},

				dragmove: function(e) {
				//  console.log(e.type);
				},

				dragend: function(e) {
				//  console.log(e.type);
				}
		  });

		  cropper = $image.data("cropper");

		  $image.on({
			"build.cropper": function(e) {
			//  console.log(e.type);
			  // e.preventDefault();
			},
			"built.cropper": function(e) {
			//  console.log(e.type);
			  // e.preventDefault();
			},
			"dragstart.cropper": function(e) {
			//  console.log(e.type);
			  // e.preventDefault();
			},
			"dragmove.cropper": function(e) {
			//  console.log(e.type);
			  // e.preventDefault();
			},
			"dragend.cropper": function(e) {
			//  console.log(e.type);
			  // e.preventDefault();
			}
		  });
/*
		  $("#reset").click(function() {
			$image.cropper("reset");
		  });

		  $("#reset2").click(function() {
			$image.cropper("reset", true);
		  });

		  $("#clear").click(function() {
			$image.cropper("clear");
		  });

		  $("#destroy").click(function() {
			$image.cropper("destroy");
		  });

		  $("#enable").click(function() {
			$image.cropper("enable");
		  });

		  $("#disable").click(function() {
			$image.cropper("disable");
		  });

		  $("#zoom").click(function() {
			$image.cropper("zoom", $("#zoomWith").val());
		  });

		  $("#zoomIn").click(function() {
			$image.cropper("zoom", 0.1);
		  });

		  $("#zoomOut").click(function() {
			$image.cropper("zoom", -0.1);
		  });

		  $("#rotate").click(function() {
			$image.cropper("rotate", $("#rotateWith").val());
		  });

		  $("#rotateLeft").click(function() {
			$image.cropper("rotate", -90);
		  });

		  $("#rotateRight").click(function() {
			$image.cropper("rotate", 90);
		  });
*/
		  var $inputImage = $("#inputImage");

		  if (window.FileReader) {
			$inputImage.change(function() {
			  var fileReader = new FileReader(),
				  files = this.files,
				  file;

			  if (!files.length) {
				return;
			  }

			  file = files[0];

			  if (/^image\/\w+$/.test(file.type)) {
				fileReader.readAsDataURL(file);
				fileReader.onload = function () {
				  $image.cropper("reset", true).cropper("replace", this.result);
				  $inputImage.val("");
				};
			  } else {
				showMessage("Please choose an image file.");
			  }
			});
		  } else {
			$inputImage.addClass("hide");
		  }

		  $("#setAspectRatio").click(function() {
			$image.cropper("setAspectRatio", $("#aspectRatio").val());
		  });

		  $("#replace").click(function() {
			$image.cropper("replace", $("#replaceWith").val());
		  });

		  $("#getImageData").click(function() {
			$("#showImageData").val(JSON.stringify($image.cropper("getImageData")));
		  });

		  $("#setData").click(function() {
			$image.cropper("setData", {
			  x: $dataX.val(),
			  y: $dataY.val(),
			  width: $dataWidth.val(),
			  height: $dataHeight.val()
			});
		  });

		  $("#getData").click(function() {
			$("#showData").val(JSON.stringify($image.cropper("getData")));
		  });

		  $("#getData2").click(function() {
			$("#showData").val(JSON.stringify($image.cropper("getData", true)));
		  });

		  $("#getDataURL").click(function() {
			var dataURL = $image.cropper("getDataURL");

			$("#dataURL").text(dataURL);
			$("#showDataURL").html('<img src="' + dataURL + '">');
		  });

		  $("#getDataURL2").click(function() {
			var dataURL = $image.cropper("getDataURL", "image/jpeg");

			$("#dataURL").text(dataURL);
			$("#showDataURL").html('<img src="' + dataURL + '">');
		  });

		  $("#getDataURL3").click(function() {
			var dataURL = $image.cropper("getDataURL", {
			  width: 160,
			  height: 90
			});

			$("#dataURL").text(dataURL);
			$("#showDataURL").html('<img src="' + dataURL + '">');
		  });  
	}
</script>

