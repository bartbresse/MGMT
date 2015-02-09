<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><?=$lang->translate('Nieuw vliegtuig');?></h2>
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
							<input type="hidden" id="id" class="form-control3312" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
							<fieldset>
								
						<div class="control-group" id="titelgroup">
							<label class="control-label" for="titel">Vliegtuig
								<ul id="titelerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("titel"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="beschrijvinggroup">
							<label class="control-label" for="beschrijving">Beschrijving
								<ul id="beschrijvingerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("beschrijving"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="staartnummergroup">
							<label class="control-label" for="staartnummer">Staart nummer
								<ul id="staartnummererror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("staartnummer"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="fileidgroup">
							<label class="control-label" for="fileid">Afbeelding
								<ul id="fileiderror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("fileid"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="firstflightgroup">
							<label class="control-label" for="firstflight">In gebruik name
								<ul id="firstflighterror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("firstflight"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="nextscheduledflightgroup">
							<label class="control-label" for="nextscheduledflight">Next scheduled flight
								<ul id="nextscheduledflighterror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("nextscheduledflight"); ?></div>
							</div>
						</div>
						
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control3312',action:'<?=$this->url->get('plane/add');?>',goto:'<?=$this->url->get('plane/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({filearray:filearray, class:'form-control3312',action:'<?=$this->url->get('plane/add');?>',goto:'<?=$this->url->get('plane/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('plane/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>	
			
		</div>
	</div>		
</div>