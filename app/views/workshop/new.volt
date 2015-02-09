
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('workshop');?> <!--<small>Out of the box form</small>--></h2>
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
										if(isset($workshop->beschrijving)){ $config['content'] = $workshop->beschrijving; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						<div class="control-group" id="categoryidgroup">							
								<label class="control-label" for="categoryidid"><?=$lang->translate("category");?>
									<ul id="categoryiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
									
										<select id="categoryid" class="categoryid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($categorys as $category){ ?>
												<option <? if(isset($workshop->id) && $category->id == $workshop->id){ echo 'selected'; } ?> value="<?=$category->id;?>"><?=$category->titel;?></option>
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
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('workshop/add');?>',goto:'<?=$this->url->get('workshop/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('workshop/add');?>',goto:'<?=$this->url->get('workshop/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('workshop/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>		
</div>



