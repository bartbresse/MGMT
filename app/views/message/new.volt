
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('message');?> <!--<small>Out of the box form</small>--></h2>
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
								<div class="control-group" id="subjectgroup">
									<label class="control-label" for="subject"><?=$lang->translate("Onderwerp");?>*
										<ul id="subjecterror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("subject"); ?></div>
									</div>
								</div>
								<div class="control-group" id="htmlgroup">
									<label class="control-label" for="beschrijving2"><?=$lang->translate("Html");?>*
										<ul id="htmlerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group" style="border:1px solid #ccc;">
										<?	//crop images settings
											$config = array('id' => 'html','class' => 'form-control');	
											if(isset($message->html)){ $config['content'] = $message->html; }
											?>
										<?php $this->partial("file/wysiwyg"); ?> 	
									</div>
								</div>
								<div class="control-group" id="togroup">
									<label class="control-label" for="to"><?=$lang->translate("Aan");?>
										<ul id="toerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("to"); ?></div>
									</div>
								</div>
								<div class="control-group" id="bccgroup">
									<label class="control-label" for="bcc"><?=$lang->translate("Bcc");?>
										<ul id="bccerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("bcc"); ?></div>
									</div>
								</div>
								<script>$('#lastedit').datetimepicker();</script>
								<script>$('#creationdate').datetimepicker();</script>
								<div class="control-group" id="templateidgroup">							
										<label class="control-label" for="templateidid"><?=$lang->translate("template");?>
											<ul id="templateiderror" class="parsley-errors-list"></ul>
										</label>
										<div class="controls form-group">
											<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
											
												<select id="templateid" class="templateid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
													<? foreach($templates as $template){ ?>
														<option <? if(isset($message->id) && $template->id == $message->id){ echo 'selected'; } ?> value="<?=$template->id;?>"><?=$template->titel;?></option>
													<? } ?>
												</select>
											
											</div>
										</div>
										<script type="text/javascript">
											 $(".templateid-multiple-select").chosen({ width: '100%' });
										</script>
									</div>
									<div class="control-group" id="tagsgroup">
										<label class="control-label" for="tags"><?=$lang->translate("Tags");?>
											<ul id="tagserror" class="parsley-errors-list"></ul>
										</label>
										<div class="controls form-group">
											<div><?php echo $form->render("tags"); ?></div>
										</div>
									</div>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('message/add');?>',goto:'<?=$this->url->get('message/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('message/add');?>',goto:'<?=$this->url->get('message/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('message/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



