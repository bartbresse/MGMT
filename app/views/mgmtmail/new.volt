<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><?=$lang->translate('Nieuwe email');?></h2>
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
								
						<div class="control-group" id="subjectgroup">
							<label class="control-label" for="subject">Subject
								<ul id="subjecterror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("subject"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="messagegroup">
							<label class="control-label" for="message">Message
								<ul id="messageerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("message"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="fromgroup">
							<label class="control-label" for="from">From
								<ul id="fromerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("from"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="togroup">
							<label class="control-label" for="to">To
								<ul id="toerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("to"); ?></div>
							</div>
						</div>
						
						<div class="control-group" id="statusgroup">
							<label class="control-label" for="status">Status
								<ul id="statuserror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("status"); ?></div>
							</div>
						</div>
						
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('mgmtmail/add');?>',goto:'<?=$this->url->get('mgmtmail/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('mgmtmail/add');?>',goto:'<?=$this->url->get('mgmtmail/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('mgmtmail/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>	
			
		</div>
	</div>		
</div>