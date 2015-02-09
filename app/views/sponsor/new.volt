
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
				<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('partner');?> <!--<small>Out of the box form</small>--></h2>
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
								<div class="control-group" id="titelgroup">
								<label class="control-label" for="titel"><?=$lang->translate("Titel");?>*
									<ul id="titelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("titel"); ?></div>
								</div>
							</div>
							<div class="control-group" id="urlgroup">
								<label class="control-label" for="url"><?=$lang->translate("Url");?>*
									<ul id="urlerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("url"); ?></div>
								</div>
							</div>
							
							<div class="control-group" id="fileid*group">							
								<label class="control-label" for="fileid"><?=$lang->translate("Foto");?>
									<ul id="fileiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls control-group">
									<div style="margin-left:15px;">
								<?	//crop images settings
									$config = array('slot' => 0,'x' => 210,'y' => 210,'cx' => 200,'cy' => 200,'id' => 23,'crop' => 'true');	?>
								<?php $this->partial("file/singleupload"); ?> 	
									</div>							
								</div>
							</div>
								<div class="control-group" id="beschrijvinggroup">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("Beschrijving");?>*
									<ul id="beschrijvingerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'beschrijving','class' => 'form-control');	
										if(isset($sponsor->beschrijving)){ $config['content'] = $sponsor->beschrijving; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						<script>$('#creationdate').datetimepicker();</script>
						<script>$('#lastedit').datetimepicker();</script>
						
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('sponsor/add');?>',goto:'<?=$this->url->get('sponsor/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('sponsor/add');?>',goto:'<?=$this->url->get('sponsor/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('sponsor/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



