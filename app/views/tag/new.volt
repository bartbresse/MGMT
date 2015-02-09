
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuwe';} ?> tag <!--<small>Out of the box form</small>--></h2>
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
							
						<?/*	
								<div class="control-group" id="berichtgroup">							
						<label class="control-label" for="fileid">bericht
							<ul id="berichtiderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="berichts" class="bericht-multiple-select form-control"  data-placeholder="Selecteer een titel...">
									<? foreach($berichts as $bericht){ ?>
	<option <? if(isset($berichttags) && in_array($bericht->id,$berichttags)){ echo 'selected'; } ?> value="<?=$bericht->id;?>"><?=$bericht->titel;?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".bericht-multiple-select").chosen({ width: '100%' });
						</script>
					</div><div class="control-group" id="eventgroup">							
						<label class="control-label" for="fileid">event
							<ul id="eventiderror" class="parsley-errors-list"></ul>
						</label>
						<div class="controls form-group">
							<div style="padding-left:0px;padding-right:0px;" class="col-sm-8 form-control" >
								<select multiple="" id="events" class="event-multiple-select form-control"  data-placeholder="Selecteer een titel...">
									<? foreach($events as $event){ ?>
	<option <? if(isset($eventtags) && in_array($event->id,$eventtags)){ echo 'selected'; } ?> value="<?=$event->id;?>"><?=$event->titel;?></option>
									<? } ?>
								</select>
							</div>
						</div>
						<script type="text/javascript">
							 $(".event-multiple-select").chosen({ width: '100%' });
						</script>
					</div>
					
					*/?>
					<div class="control-group" id="titelgroup">
								<label class="control-label" for="titel">Titel
									<ul id="titelerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("titel"); ?></div>
								</div>
							</div>
							<script>$('#lastedit').datetimepicker();</script>
						<script>$('#creationdate').datetimepicker();</script>
						
						<?/*
						<div class="control-group" id="entityidgroup">							
								<label class="control-label" for="entityidid">entity
									<ul id="entityiderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div style="padding-left:0px;padding-right:0px;" class="col-sm-8" >
									
										<select id="entityid" class="entityid-multiple-select form-control"  data-placeholder="Choose a Department..."  tabindex="2">
											<? foreach($entitys as $entity){ ?>
												<option <? if(isset($tag->id) && $entity->id == $tag->id){ echo 'selected'; } ?> value="<?=$entity->id;?>"><?=$entity->title;?></option>
											<? } ?>
										</select>
									
									</div>
								</div>
								<script type="text/javascript">
									 $(".entityid-multiple-select").chosen({ width: '100%' });
								</script>
							</div><div class="control-group" id="entitygroup">
								<label class="control-label" for="entity">Entity
									<ul id="entityerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("entity"); ?></div>
								</div>
							</div>*/?>
							
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('tag/add');?>',goto:'<?=$this->url->get('tag/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('tag/add');?>',goto:'<?=$this->url->get('tag/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('tag/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			 

		</div>
	</div>		
</div>



