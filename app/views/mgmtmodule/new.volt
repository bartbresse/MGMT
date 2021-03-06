<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><?=$lang->translate('Nieuwe module');?></h2>
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
								<div class="control-group" id="fileidgroup">
									<label class="control-label" for="fileid">Titel
										<ul id="fileiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("titel"); ?></div>
									</div>
								</div>
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({class:'form-control',action:'<?=$this->url->get('mgmtmodule/add');?>',goto:'<?=$this->url->get('mgmtmodule/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({class:'form-control',action:'<?=$this->url->get('mgmtmodule/add');?>',goto:'<?=$this->url->get('mgmtmodule/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('mgmtmodule/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>	
			
		</div>
	</div>		
</div>