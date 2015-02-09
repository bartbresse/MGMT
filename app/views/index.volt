<!DOCTYPE html> 
<html>
    <head> 
		<meta charset="utf-8">
		{{ get_title() }}
		<meta name="robots" content="noindex, nofollow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		{{ stylesheet_link('css/chosen.css') }} 	
		{{ javascript_include('js/chosen.jquery.js') }}		
		<!--<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />-->
		{{ stylesheet_link('css/jqueryui.css') }}   
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		{{ stylesheet_link('css/style.css') }}   
		{{ javascript_include('js/holder.js') }}
		{{ stylesheet_link('js/jcrop/jquery.Jcrop.min.css') }}   
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		{{ stylesheet_link('redactor/redactor.css') }} 
		{{ javascript_include('redactor/redactor.js') }}
		{{ stylesheet_link('css/datetimepicker.css') }} 
		{{ javascript_include('js/datetimepicker.js') }}
		{{ javascript_include('js/dropzone.js') }}
		<!-- jquery plugin piechart -->
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
		{{ javascript_include('js/jquery.easypiechart.min.js') }}
		<!-- jquery plugin piechart end -->
		<script>
			args = [];	
			args.saves = []; 
			args.data = [];
		</script>
                {{ javascript_include('js/register.js') }}
                
		{{ javascript_include('js/utils.js') }}
                {{ javascript_include('js/search.js') }}
		{{ javascript_include('js/form.js') }}		
		{{ javascript_include('js/app.js') }}	
                {{ javascript_include('js/email.js') }}	
		<script>
			//pull down column select
			height = $('#sidebar').height();
			top = $('#sidebar').css('top');
			total = height+25;
			$('#selectsidebar').css('top',total);

			//init javascript vars
			<?php if(isset($_GET["_url"])){ $entity = explode('/',$_GET["_url"]); ?>
			args.baseurl = '<?php echo  $this->url->get(); ?>';
			args.entity = '<?php echo $entity[1]."/"; ?>';
			<? }else{ ?>args = [];args.baseurl = '';args.entity = '';  <? } ?>
			//alert(args.baseurl);	
			//inittable(args);
			filearray = [];		
		</script>	
	</head>
	<?
		$auth = $this->session->get('auth');
		if(!$auth)
		{
			$class='login-background';
		}else{$class = '';}
	
	?>
         <body class="background-dark <?=$class;?>">
		{{ content() }}
		{{ javascript_include('js/table.js') }}
		<script>
			// alert(args.baseurl);
			args.baseurl = '<?=$this->url->get();?>';
			inittable(args);

			
			//redactor init code
                        /*
			$(document).ready(
				function()
				{
					if (!RedactorPlugins) var RedactorPlugins = {};
				
					RedactorPlugins.advanced = function()
					{
						return { init: function(){ var button = this.button.add('advanced', 'Advanced');
                                                            // make your added button as Font Awesome's icon
                                                            // this.button.setAwesome('advanced', 'fa-tasks');
                                                            // this.button.addCallback(button, this.advanced.testButton);
							},
							testButton: function(buttonName)
							{
                                                            alert(buttonName);
							}
						};
					};
				
					$('.redactor').redactor({
						fileUpload: '../demo/scripts/file_upload.php',
						plugins: ['advanced']
					});
					
					if (!RedactorPlugins) var RedactorPlugins = {};
				
					RedactorPlugins.advanced = function()
					{
						return { init: function(){ var button = this.button.add('advanced', 'Advanced');
								// make your added button as Font Awesome's icon
							//	this.button.setAwesome('advanced', 'fa-tasks');
							//	this.button.addCallback(button, this.advanced.testButton);
							},
							testButton: function(buttonName)
							{
								alert(buttonName);
							}
						};
					};
				
				
				}
				
				
			);*/
			
			//slect boxes
			$(".select").chosen({ width: '100%' });
			//datepickers
			$('.date').datepicker();
			$('.datetime').datetimepicker();
		</script>
		{{ javascript_include('js/jcrop/jquery.Jcrop.min.js') }}
 	</body>
</html>


