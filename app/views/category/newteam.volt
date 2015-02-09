
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> team <!--<small>Out of the box form</small>--></h2>
		    </div>
		</div>
		<div class="row">
		    <div class="col-md-7">
		        <section class="widget" id="mgmt-table-container">
		            <header>
		             <!--   <h4><i class="fa fa-user"></i> Account Profile <small>Create new or edit existing user</small></h4>-->
					</header>
					<div class="body">
						<div id="user-form" class="form-horizontal label-left" data-parsley-priority-enabled="false" novalidate="">
							<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
							<input type="hidden" id="niveau" class="form-control" value="3" />
							<fieldset>
								<div class="control-group" id="titelgroup">
								<label class="control-label" for="titel">Titel*
									<ul id="titelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("titel"); ?></div>
								</div>
								<div class="control-group" id="headergroup">
									<label class="control-label" for="header">Header tekst
										<ul id="headererror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("header"); ?></div>
									</div>
								</div>	
								<div class="control-group" id="hoofdtitelgroup">
									<label class="control-label" for="hoofdtitel">Hoofdtitel
										<ul id="hoofdtitelerror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("hoofdtitel"); ?></div>
									</div>
								</div>		
								</div>
									<div class="control-group" id="beschrijving1group">
									<label class="control-label" for="beschrijving2">Eerst alinea
										<ul id="beschrijving1error" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group" style="border:1px solid #ccc;">
										<?	//crop images settings
											$config = array('id' => 'beschrijving1','class' => 'form-control');	
											if(isset($category->beschrijving1)){ $config['content'] = $category->beschrijving1; }
											?>
										<?php $this->partial("file/wysiwyg"); ?> 	
									</div>
								</div>
								<div class="control-group" id="beschrijving2group">
									<label class="control-label" for="beschrijving2">Tweede alinea
										<ul id="beschrijving2error" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group" style="border:1px solid #ccc;">
										<?	//crop images settings
											$config = array('id' => 'beschrijving2','class' => 'form-control');	
											if(isset($category->beschrijving2)){ $config['content'] = $category->beschrijving2; }
											?>
										<?php $this->partial("file/wysiwyg"); ?> 	
									</div>
								</div>
								<input type="hidden" value="3" id="niveau" class="form-control" />	
								<div class="control-group" id="parentidgroup">							
									<label class="control-label" for="parentid">plaats*
										<ul id="parentiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div><?php echo $form->render("parentid"); ?></div>			
									</div>
								</div>
								<div class="control-group" id="fileidgroup">							
									<label class="control-label" for="fileid">Foto
										<ul id="fileiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls control-group">
										<p>Zorg dat je altijd een grote upload, het resultaat kan tegenvallen op grote beeldschermen van foto's met een lage resolutie.</p>
										<div style="margin-left:15px;">
									<?	//crop images settings
										$config = array('slot' => 0,'x' => 475,'y' => 110,'cx' => 800,'cy' => 200,'id' => 23,'crop' => 'true','resize' => 'false','aspectratio' => 4.2,'cropwithin' => 'true');	?>
								  <?php $this->partial("file/singleupload"); ?> 	
										</div>							
									</div>
								</div>
								<script>$('#lastedit').datetimepicker();</script>
								<script>$('#creationdate').datetimepicker();</script>
								<div class="control-group" id="taggroup">							
									<label class="control-label" for="fileid"><?=$lang->translate("tag");?>
										<ul id="tagiderror" class="parsley-errors-list"></ul>
									</label>
									<div class="controls form-group">
										<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
											<select multiple="" id="tags" class="tag-multiple-select form-control"  data-placeholder="Selecteer tags...">
												<? foreach($tags as $tag){ ?>
				<option <? if(isset($categorytags) && in_array($tag->id,$categorytags)){ echo 'selected'; } ?> value="<?=$tag->id;?>"><?=$tag->titel;?></option>
												<? } ?>
											</select>
										</div>
									</div>
									<script type="text/javascript">
										 $(".tag-multiple-select").chosen({ width: '100%' });
									</script>
								</div>
								
							
							</fieldset>     
						
							<div class="form-actions" class="pull-right">
								<? if(isset($_GET['niveau'])){ $niveau = '&niveau='.$_GET['niveau']; }else{ $niveau = ''; }?>
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('category/add');?>',goto:'<?=$this->url->get('category/index'.$niveau);?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('category/add');?>',goto:'<?=$this->url->get('category/new'.$niveau);?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('category/').$niveau;?>');" class="btn btn-default">Cancel</button> 
							</div>
						</div>
					</div>
				</section>
			</div>
 
			<!-- getfunctions start custom widget -->
			<div class="col-md-4">
				<section class="widget">		
					<div id="acl-control">
						<?=$this->partial("file/relaties");?> 
					</div>
				</section>
			</div>
			<!-- end custom widget -->
		</div>
	</div>		
</div>



