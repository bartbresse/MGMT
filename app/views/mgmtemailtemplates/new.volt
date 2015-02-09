<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><?=$lang->translate('Nieuw email template');?></h2>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-7">
		        <section class="widget">
					<div class="body">
						<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
							<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
							<fieldset>
                                                            <div class="control-group" id="titelgroup">
                                                                <label class="control-label" for="titel"><?=$lang->translate("Titel");?>*
                                                                    <ul id="titelerror" class="parsley-errors-list"></ul>
                                                                </label>
                                                                <div class="controls form-group">
                                                                    <div><?php echo $form->render("titel"); ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="" id="titelgroup">
                                                                <div><?php echo $form->render("template"); ?></div>
                                                            </div>
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('mgmtemailtemplates/add');?>',goto:'<?=$this->url->get('mgmtemailtemplates/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('mgmtemailtemplates/add');?>',goto:'<?=$this->url->get('mgmtemailtemplates/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('mgmtemailtemplates/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>	
					<script>
						function cropimage(path,o)
						{
							$('.cropper').attr('src',path);
							$('.cropper').on('load', function() {
								$('#cropimage').modal('show');			
								initcropper();
							});
						}
						
						
					
					
						
					</script>
					
					<input type="hidden" value="" id="croppedimage"/>
					
					<!-- add image -->
					<div id="selectimage" class="modal fade">
					  <div class="modal-dialog modal-lg">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Selecteer een plaatje</h4>
						  </div>
						  <div class="modal-body">
							<div><? $showupload = true; echo $this->partial("media/grid");?></div>
							<div style="clear:both;"></div>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
						  </div>
						</div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
					
					<!-- add header image -->
					<div id="selectheaderimage" class="modal fade">
					  <div class="modal-dialog modal-lg">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Selecteer een plaatje</h4>
						  </div>
						  <div class="modal-body">
							<div><? $showupload = true; echo $this->partial("media/grid");?></div>
							<div style="clear:both;"></div>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
						  </div>
						</div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
					<!-- crop image -->
					<div id="cropimage" class="modal fade">
						<div class="modal-dialog modal-md">
							<div class="modal-content">
								<link href="<?=$this->url->get('css/cropper.css');?>" rel="stylesheet">
								<link href="<?=$this->url->get('css/docs.css');?>" rel="stylesheet">
								<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
								<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
								<!--[if lt IE 9]>
								<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
								<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
								<![endif]-->
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title">Crop foto</h4>
								</div>
								<div class="modal-body">
									<div class="eg-wrapper">
										<img class="cropper" src="" alt="Picture">
									</div>
									<div style="clear:both;"></div>
									<br /><br />
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
									<button type="button" onclick="saveupload('<?=$this->url->get();?>');" class="btn btn-primary">Opslaan</button>
								</div>
								<script src="<?=$this->url->get('js/cropper.js');?>"></script>
								<script src="<?=$this->url->get('js/docs.js');?>"></script>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					

				</section>
			</div>	
			<div class="col-md-5">
		        <section class="widget">
					<h3>Page layout</h3>
					
					<a onclick="addHeader();">add header</a>
					<a onclick="addImage();">add image</a>
					<a onclick="addText();">add text block</a>
					<a onclick="addHeaderImage();">add header image</a>
					<a>add two column</a>
					<a>add footer</a>
					
				</section>
				<script>
					function addHeader()
					{
						$('.redactor_form-control').append('<table style="width:100%;"><tr><td></td></tr></table>');
					}
				
					function addImage()
					{
						$('#selectimage').modal('show');	
					}	
					
					function addHeaderImage()
					{
						$('#selectheaderimage').modal('show');	
					}	
					
					function addText()
					{
						
					}
					
					$('#croppedimage').change(function(){	
						alert('change');
						path = $('#croppedimage').val();
						alert(path);
						$('#selectheaderimage').modal('hide');
					});
				</script>
			</div>
			
		</div>
	</div>		
</div>