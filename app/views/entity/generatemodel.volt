{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title">Generate model<small></small></h2>
		        </div>
		    </div>
		    <div class="row">
		        <div class="col-md-12">
		            <section class="widget" id="mgmt-table-container">
		                <header> 		
		                    <h4>
		                        <i class="fa fa-file-alt"></i>
		                     
		                    </h4>
		                </header>
		                <div class="body">
							
							<div class="control-group" id="tablegroup">
								<label class="control-label" for="table"><?=$lang->translate("Table");?>*
									<ul id="tableerror" class="parsley-errors-list"></ul>
								</label>
								<div class="controls form-group">
									<div><?php echo $form->render("table"); ?></div>
								</div>
							</div>
							
							<button type="submit" onclick="save({class:'form-control',action:'<?=$this->url->get('entity/addmodel');?>',goto:'<?=$this->url->get('entity/index');?>'});" class="btn btn-primary">Generate</button>
							
						</div>
		            </section>
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
		
		
	</div>
</div>


