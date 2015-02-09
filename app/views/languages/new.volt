<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><?=$lang->translate('Edit Translations ');?></h2>
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
								
						<div class="control-group" id="enggroup">
							<label class="control-label" for="eng">Eng
								<ul id="engerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("eng"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="nlgroup">
							<label class="control-label" for="nl">Nl
								<ul id="nlerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("nl"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="degroup">
							<label class="control-label" for="de">De
								<ul id="deerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("de"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="spgroup">
							<label class="control-label" for="sp">Sp
								<ul id="sperror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("sp"); ?></div>
							</div>
						</div>
						
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('languages/add');?>',goto:'<?=$this->url->get('languages/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('languages/add');?>',goto:'<?=$this->url->get('languages/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('languages/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>	
			
		</div>
	</div>		
</div>