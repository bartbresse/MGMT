
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> <?=$lang->translate('berichtreactie');?> <!--<small>Out of the box form</small>--></h2>
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
								<div class="control-group" id="berichtidgroup">							
								<label class="control-label" for="berichtidid"><?=$lang->translate("bericht");?>
									<ul id="berichtiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
									
										<select id="berichtid" class="berichtid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($berichts as $bericht){ ?>
												<option <? if(isset($berichtreactie->id) && $bericht->id == $berichtreactie->id){ echo 'selected'; } ?> value="<?=$bericht->id;?>"><?=$bericht->titel;?></option>
											<? } ?>
										</select>
									
									</div>
								</div>
								<script type="text/javascript">
									 $(".berichtid-multiple-select").chosen({ width: '100%' });
								</script>
							</div>	<div class="control-group" id="berichtgroup">
								<label class="control-label" for="beschrijving2"><?=$lang->translate("Bericht");?>*
									<ul id="berichterror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'bericht','class' => 'form-control');	
										if(isset($berichtreactie->bericht)){ $config['content'] = $berichtreactie->bericht; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						
							<?/*
							<div class="control-group" id="parentidgroup">							
								<label class="control-label" for="parentid">Bericht
									<ul id="parentiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("parentid"); ?></div>			
								</div>
							</div>
							*/?>
							
							<script>$('#creationdate').datetimepicker();</script>
						<script>$('#lastedit').datetimepicker();</script>
						<?/*
						<div class="control-group" id="nummergroup">
								<label class="control-label" for="nummer"><?=$lang->translate("Nummer");?>*
									<ul id="nummererror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("nummer"); ?></div>
								</div>
							</div>
							*/?>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('berichtreactie/add');?>',goto:'<?=$this->url->get('berichtreactie/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('berichtreactie/add');?>',goto:'<?=$this->url->get('berichtreactie/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('berichtreactie/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



