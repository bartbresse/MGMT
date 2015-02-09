{{ content() }}
<div class="wrap">
	 <div class="content container">
		    <div class="row">
		        <div class="col-md-12">
					<h2 style="float:left;" class="page-title">Translations<small><a href=""><i class="fa fa-question"></i></a></small></h2>
					<div class="pull-right">
						<a title="gridview" id="gridview"><i style="margin-right:10px;" class="fa fa-th fa-2x"></i></a>
						<a title="listview" id="listview"><i class="fa fa-th-list fa-2x"></i></a>
					</div>
				</div>
		    </div>
		    <div class="row">
		        <div class="col-md-12" id="mgmt-table-container">
					<?=$this->partial("languages/cleaninline");?> 
		        </div>
				<?=$this->partial("table/selectcolumns");?> 
		    </div>
		</div>
	</div>
</div>