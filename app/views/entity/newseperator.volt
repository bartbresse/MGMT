<div class="wrap">
	 <div class="content container">
		    {{ javascript_include('js/entity.js') }}
			<div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title"><?=$lang->translate('Nieuwe separatie');?></h2>
		        </div>
		    </div>
		    <div class="row">
				 <div class="col-md-12">
					<section class="widget" id="mgmt-table-container">
						
						<div class="control-group" id="titelgroup">
							<label class="control-label" for="titel"><?=$lang->translate("Titel");?>*
								<ul id="titelerror" class="parsley-errors-list"></ul>
							</label>
							<div class="controls form-group">
								<div><?php echo $form->render("titel"); ?></div>
							</div>
						</div>
						
						<div>
							<button type="button" onclick="save({ class:'form-control',action:'<?=$this->url->get('entity/addseperator');?>',goto:'<?=$this->url->get('entity');?>'});" class="btn btn-primary">Opslaan</button>
						</div>
					</section>
				</div>
		    </div>
		</div>
	</div>
</div>
