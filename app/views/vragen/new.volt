
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> vragen <!--<small>Out of the box form</small>--></h2>
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
								
							<div class="control-group" id="vraaggroup">
								<label class="control-label" for="beschrijving2">Vraag
									<ul id="vraagerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'vraag','class' => 'form-control');	
										if(isset($vragen->vraag)){ $config['content'] = $vragen->vraag; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						
							<div class="control-group" id="antwoordgroup">
								<label class="control-label" for="beschrijving2">Antwoord
									<ul id="antwoorderror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group" style="border:1px solid #ccc;">
									<?	//crop images settings
										$config = array('id' => 'antwoord','class' => 'form-control');	
										if(isset($vragen->antwoord)){ $config['content'] = $vragen->antwoord; }
										?>
									<?php $this->partial("file/wysiwyg"); ?> 	
								</div>
							</div>
						<script>$('#creationdate').datetimepicker();</script>
						<script>$('#lastedit').datetimepicker();</script>
						
							</fieldset>                       
							<div class="form-actions" class="pull-right">
								<button type="submit" onclick="save({filearray:filearray, class:'form-control',action:'<?=$this->url->get('vragen/add');?>',goto:'<?=$this->url->get('vragen/index');?>'});" class="btn btn-primary">Opslaan</button>
								<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('vragen/add');?>',goto:'<?=$this->url->get('vragen/new');?>'});" class="btn btn-primary">Opslaan + Nieuw</button>
								<button type="button" onclick="go('<?=$this->url->get('vragen/index');?>');" class="btn btn-default">Cancel</button>
							</div>
						</div>
					</div>
				</section>
			</div>
					
			

		</div>
	</div>		
</div>



