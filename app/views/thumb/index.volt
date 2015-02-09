{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
		            <h2 style="float:left;" class="page-title">Thumbs<small></small></h2>
					<div style="float:right;">
						<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
						<button class="btn btn-primary" onclick="save({class:'form-control',action:'<?=$this->url->get('thumb/generate');?>',goto:false});">
							<i class="fa fa-plus"></i>Generate thumbs
						</button>
					</div>
		        </div>
		    </div>
		    <div class="row">
		        <div class="col-md-12">
		            <section class="widget" id="mgmt-table-container">
						<?=$this->partial("thumb/clean");?> 
		            </section>
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
	</div>
</div>