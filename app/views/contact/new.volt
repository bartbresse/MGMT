
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('contact');?> <!--<small>Out of the box form</small>--></h2>
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
								<div class="control-group" id="naamgroup">
								<label class="control-label" for="naam"><?=$lang->translate("Naam");?>
									<ul id="naamerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("naam"); ?></div>
								</div>
							</div>
							<div class="control-group" id="emailgroup">
								<label class="control-label" for="email"><?=$lang->translate("Email");?>*
									<ul id="emailerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("email"); ?></div>
								</div>
							</div>
							<div class="control-group" id="telefoongroup">
								<label class="control-label" for="telefoon"><?=$lang->translate("Telefoon");?>
									<ul id="telefoonerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("telefoon"); ?></div>
								</div>
							</div>
								<div class="control-group" id="berichtgroup">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("Bericht");?>*
									<ul id="berichterror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'bericht','class' => 'form-control');	
										if(isset($contact->bericht)){ $config['content'] = $contact->bericht; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						<div class="control-group" id="postgroup">
								<label class="control-label" for="post"><?=$lang->translate("Post");?>*
									<ul id="posterror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("post"); ?></div>
								</div>
							</div>
							<script>$('#post').datetimepicker();</script>
						<div class="control-group" id="nieuwsbriefgroup">
								<label class="control-label" for="nieuwsbrief"><?=$lang->translate("Nieuwsbrief");?>
									<ul id="nieuwsbrieferror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("nieuwsbrief"); ?></div>
								</div>
							</div>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('contact/add');?>',goto:'<?=$this->url->get('contact/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('contact/add');?>',goto:'<?=$this->url->get('contact/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('contact/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



