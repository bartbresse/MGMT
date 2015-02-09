
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('bericht');?> <!--<small>Out of the box form</small>--></h2>
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
								<div class="control-group" id="taggroup">							
						<label class="control-label" for="fileid"><?=$lang->translate("tag");?>
							<ul id="tagiderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="tags" class="tag-multiple-select form-control"  data-placeholder="Selecteer een titel...">
									<? foreach($tags as $tag){ ?>
	<option <? if(isset($berichttags) && in_array($tag->id,$berichttags)){ echo 'selected'; } ?> value="<?=$tag->id;?>"><?=$tag->titel;?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".tag-multiple-select").chosen({ width: '100%' });
						</script>
					</div><div class="control-group" id="titelgroup">
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
										if(isset($bericht->beschrijving)){ $config['content'] = $bericht->beschrijving; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>

							<div>
								test
								
								<?php echo $form->render("file"); ?>
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
							
							<script>$('#creationdate').datetimepicker();</script>
							<script>$('#lastedit').datetimepicker();</script>
						
							<div class="control-group" id="categoryidgroup">							
								<label class="control-label" for="categoryidid">Team/Plaats
									<ul id="categoryiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
										<select id="categoryid" class="categoryid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($categorys as $category){ ?>
											<option <? if(isset($bericht->id) && $category->id == $bericht->id){ echo 'selected'; } ?> value="<?=$category->id;?>"><?=$category->titel;?></option>
											<? } ?>
										</select>
									</div>
								</div>
								<script type="text/javascript">
									 $(".categoryid-multiple-select").chosen({ width: '100%' });
								</script>
							</div>	
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('bericht/add');?>',goto:'<?=$this->url->get('bericht/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('bericht/add');?>',goto:'<?=$this->url->get('bericht/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('bericht/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



