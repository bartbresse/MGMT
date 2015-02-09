
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('userinfo');?> <!--<small>Out of the box form</small>--></h2>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-7">
		        <section class="widget">
		            <header>
		             <!--   <h4><i class="fa fa-user"></i> Account Profile <small>Create new or edit existing user</small></h4>-->
					</header>
					<div class="body">
						<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
							<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
							<fieldset>
								<div class="control-group" id="httpreferergroup">
								<label class="control-label" for="httpreferer"><?=$lang->translate("Httpreferer");?>*
									<ul id="httpreferererror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("httpreferer"); ?></div>
								</div>
							</div>
							<div class="control-group" id="remoteaddrgroup">
								<label class="control-label" for="remoteaddr"><?=$lang->translate("Remoteaddr");?>*
									<ul id="remoteaddrerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("remoteaddr"); ?></div>
								</div>
							</div>
							<div class="control-group" id="httpuseragentgroup">
								<label class="control-label" for="httpuseragent"><?=$lang->translate("Httpuseragent");?>*
									<ul id="httpuseragenterror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("httpuseragent"); ?></div>
								</div>
							</div>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('userinfo/add');?>',goto:'<?=$this->url->get('userinfo/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('userinfo/add');?>',goto:'<?=$this->url->get('userinfo/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('userinfo/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



