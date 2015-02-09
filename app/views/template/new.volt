
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('template');?> <!--<small>Out of the box form</small>--></h2>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-7">
		        <section class="widget">
		            <header>
		             <!--   <h4><i class="fa fa-user"></i>Account Profile<small>Create new or edit existing user</small></h4>-->
					</header>
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
								<div class="control-group" id="filenamegroup">
									<label class="control-label" for="filename"><?=$lang->translate("Filename");?>*
										<ul id="filenameerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("filename"); ?></div>
									</div>
								</div>
								
								<div class="control-group" id="filenamegroup">
									<label class="control-label" for="filename"><?=$lang->translate("Filename");?>*
										<ul id="filenameerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("filename"); ?></div>
									</div>
								</div>
								
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('template/add');?>',goto:'<?=$this->url->get('template/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('template/add');?>',goto:'<?=$this->url->get('template/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('template/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



