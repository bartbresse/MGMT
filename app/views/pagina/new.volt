
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuwe';} ?> <?=$lang->translate('pagina');?> <!--<small>Out of the box form</small>--></h2>
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
								<label class="control-label" for="titel"><?=$lang->translate("Titel");?>
									<ul id="titelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("titel"); ?></div>
								</div>
							</div>
								<div class="control-group" id="beschrijvinggroup">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("Beschrijving");?>
									<ul id="beschrijvingerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'beschrijving','class' => 'form-control');	
										if(isset($pagina->beschrijving)){ $config['content'] = $pagina->beschrijving; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						
							<div class="control-group" id="fileidgroup">							
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
							<script>$('#creationdate').datetimepicker();</script>
						<script>$('#lastedit').datetimepicker();</script>
							<div class="control-group" id="urlgroup">
								<label class="control-label" for="url"><?=$lang->translate("Url");?>*
									<ul id="urlerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("url"); ?></div>
								</div>
							</div>
							<div class="control-group" id="niveaugroup">
								<label class="control-label" for="niveau"><?=$lang->translate("Niveau");?>
									<ul id="niveauerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
										<select id="niveau" name="niveau" class="niveau-multiple-select form-control"  data-placeholder="Choose a Level..."  tabindex="2">
											<option <? if(isset($pagina->niveau) && $pagina->niveau == 0){ echo 'selected'; } ?> value="-1">Vaste pagina</option>
											<option <? if(isset($pagina->niveau) && $pagina->niveau == 0){ echo 'selected'; } ?> value="0">Topmenu</option>
											<option <? if(isset($pagina->niveau) && $pagina->niveau == 1){ echo 'selected'; } ?> value="1">Hoofdmenu</option>
											<option <? if(isset($pagina->niveau) && $pagina->niveau == 2){ echo 'selected'; } ?> value="2">Submenu</option>
										</select>
										<script type="text/javascript">
											$("select#niveau").change(function() {
												$("#categoryidgroup").hide();
												$("#parentidgroup").hide();
												if($(this).val()==1) $("#categoryidgroup").show();
												if($(this).val()==2) $("#parentidgroup").show();
											});
										</script>
									</div>
								</div>
							</div>
								<div <? if(!isset($pagina->niveau) || $pagina->niveau == 0 || $pagina->niveau == 2){ echo 'style="display:none"'; } ?> class="control-group" id="categoryidgroup">							
									<label class="control-label" for="categoryidid"><?=$lang->translate("Categorie/pagina");?>
										<ul id="categoryiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
											<select id="categoryid" name="categoryid" class="categoryid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
												<? foreach($categorys as $category){ ?>
													<option <? if(isset($pagina->id) && $category->id == $pagina->categoryid){ echo 'selected'; } ?> value="<?=$category->id;?>"><?=$category->titel;?></option>
												<? } ?>
												<? foreach($hoofdmenupages as $page){ 
													if($page->id != $pagina->id) {
													?>
													<option <? if(isset($pagina->id) && $page->id == $pagina->categoryid){ echo 'selected'; } ?> value="<?=$page->id;?>"><?=$page->titel;?></option>
												<? 	} 
													}?>
												<? foreach($submenupaginas as $page){ 
													if($page->id != $pagina->id) {
													?>
													<option <? if(isset($pagina->id) && $page->id == $pagina->categoryid){ echo 'selected'; } ?> value="<?=$page->id;?>"><?=$page->titel;?></option>
												<? 	} 
													}?>
											</select>
										
										</div>
									</div>
									<script type="text/javascript">
										 $(".categoryid-multiple-select").chosen({ width: '100%' });
									</script>
								</div>
							
								<div <? if(!(isset($pagina->niveau) && $pagina->niveau == 2)){ echo 'style="display:none"'; } ?> class="control-group" id="parentidgroup">							
									<label class="control-label" for="parentid"><?=$lang->translate("Hoofdpagina");?>
										<ul id="categoryiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
											<select id="categoryid2" name="categoryid2" class="categoryid-multiple-select form-control"  data-placeholder="Choose a parent..."  tabindex="2">
												<? foreach($categorys as $category){ ?>
													<option <? if(isset($pagina->id) && $category->id == $pagina->categoryid){ echo 'selected'; } ?> value="<?=$category->id;?>"><?=$category->titel;?></option>
												<? } ?>
												<? foreach($submenupaginas as $page){
													if($page->id != $pagina->id) {
													?>
													<option <? if(isset($pagina->id) && $page->id == $pagina->categoryid){ echo 'selected'; } ?> value="<?=$page->id;?>"><?=$page->titel;?></option>
												<?  }
													}	?>
											</select>
										</div>
									</div>
									<script type="text/javascript">
										 $(".categoryid-multiple-select").chosen({ width: '100%' });
									</script>
								</div>
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('pagina/add');?>',goto:'<?=$this->url->get('pagina/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('pagina/add');?>',goto:'<?=$this->url->get('pagina/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('pagina/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



