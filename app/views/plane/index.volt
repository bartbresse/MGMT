{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title">Vliegtuigen<small></small></h2>
		        </div>
		    </div>
		    <div class="row">
		        <div class="col-md-12">
		            <section class="widget" id="mgmt-table-container">
						<?=$this->partial("plane/clean");?> 
		            </section>
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
	</div>
</div>