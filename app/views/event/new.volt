<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> evenement </h2>
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
								<div class="control-group" id="beschrijvinggroup">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("Beschrijving");?>*
									<ul id="beschrijvingerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'beschrijving','class' => 'form-control');	
										if(isset($event->beschrijving)){ $config['content'] = $event->beschrijving; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						<script>$('#creationdate').datetimepicker();</script>
						<script>$('#lastedit').datetimepicker();</script>
						<div class="control-group" id="startgroup">
								<label class="control-label" for="start"><?=$lang->translate("Start");?>*
									<ul id="starterror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("start"); ?></div>
								</div>
							</div>
							<script>$('#start').datetimepicker();</script>
						<div class="control-group" id="eindegroup">
								<label class="control-label" for="einde"><?=$lang->translate("Einde");?>*
									<ul id="eindeerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("einde"); ?></div>
								</div>
							</div>
							<script>$('#einde').datetimepicker();</script>
						
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
							
						<!--<div class="control-group" id="categorygroup">
								<label class="control-label" for="category"><?=$lang->translate("Categorie");?>*
									<ul id="categoryerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<select id="category" class="form-control">
										<? $workshops = array('Workshop','Bijeenkomst','Evenement','Picknick');
										   foreach($workshops as $work){ ?>
										<option <? if(isset($event->category) && $work == $event->category){ echo 'selected'; } ?> value="<?=$work;?>"><?=$work;?></option>
										<? } ?>
									</select>
								</div>
							</div>-->
							
							<div class="control-group" id="categoryidgroup">							
								<label class="control-label" for="categoryidid">Plaats / Team
									<ul id="categoryiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
									
										<select id="categoryid" class="categoryid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($categorys as $category){ ?>
												<option <? if(isset($event->id) && $category->id == $event->id){ echo 'selected'; } ?> value="<?=$category->id;?>"><?=$category->titel;?></option>
											<? } ?>
										</select>
									
									</div>
								</div>
								<script type="text/javascript">
									 $(".categoryid-multiple-select").chosen({ width: '100%' });
								</script>
							</div>
							
							<div class="control-group" id="locatiegroup">
								<label class="control-label" for="locatie"><?=$lang->translate("Locatie");?>
									<ul id="locatieerror" class="parsley-errors-list"></ul>
								</label>
								
								<div class="controls form-group">
									<div>
										<p style="padding-left:20px;">  Vul hier de locatie in bijvoorbeeld: "Straat 23, Plaats" </p>
										<?php echo $form->render("locatie"); ?>
									</div>
								</div>
								
							</div>
					
					<?/*	
					<div class="control-group" id="taggroup">							
						<label class="control-label" for="fileid"><?=$lang->translate("tag");?>
							<ul id="tagiderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="tags" class="tag-multiple-select form-control"  data-placeholder="Selecteer een titel...">
									<? foreach($tags as $tag){ ?>
	<option <? if(isset($eventtags) && in_array($tag->id,$eventtags)){ echo 'selected'; } ?> value="<?=$tag->id;?>"><?=$tag->titel;?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".tag-multiple-select").chosen({ width: '100%' });
						</script>
					</div>
					*/?>
					
					<div class="control-group" id="usergroup">							
						<label class="control-label" for="fileid">Deelnemers
							<ul id="useriderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="users" class="user-multiple-select form-control"  data-placeholder="Selecteer een naam...">
									<? foreach($users as $user){ ?>
	<option <? if(isset($eventusers) && in_array($user->id,$eventusers)){ echo 'selected'; } ?> value="<?=$user->id;?>"><?=$user->firstname;?> <?=$user->insertion;?> <?=$user->lastname;?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".user-multiple-select").chosen({ width: '100%' });
						</script>
					</div>
							
							<?/*	
							<div class="control-group" id="aanmeldingengroup">
								<label class="control-label" for="aanmeldingen"><?=$lang->translate("Aanmeldingen");?>
									<ul id="aanmeldingenerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("aanmeldingen"); ?></div>
								</div>
							</div>
							<div class="control-group" id="statusgroup">
								<label class="control-label" for="status"><?=$lang->translate("Status");?>
									<ul id="statuserror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("status"); ?></div>
								</div>
							</div>
							*/?>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('event/add');?>',goto:'<?=$this->url->get('event/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('event/add');?>',goto:'<?=$this->url->get('event/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('event/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 
							<!-- getfunctions start custom widget -->
						<!--	<div class="col-md-4">
								<section class="widget">		
									<div id="tag-control">
										<?=$this->partial("file/generatetag");?> 
									</div>
								</section>
							</div>-->
							<!-- end custom widget -->
							

		</div>
	</div>		
</div>



