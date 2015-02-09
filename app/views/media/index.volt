<div class="wrap">
	<script>
		function eventHandler()
		{
			this.handlers = {};
		
			this.attach = function()
			{
				
			}
		
			this.notify = function()
			{
				
			}
		}
		eventhandler  = new eventHandler();
	</script>
	<div class="content container">
		<div class="row">
			<div class="col-md-12">
				<h2 class="page-title"><?=$lang->translate('Bestanden');?></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<section class="widget">
					<? //$this->partial("file/multipleupload");?> 
					<?=$form->render('file');?>
				</section>
			</div>
		</div>
		<div class="row">
			<div id="mgmt-table-container">
				<?=$this->partial("media/grid");?> 
			</div>
		</div>
	</div>
</div>

