<div class="wrap">
	 <div class="content container">
		    {{ javascript_include('js/entity.js') }}
			<div class="row">
		        <div class="col-md-12">
		            <h2 class="page-title"><?=$lang->translate('Nieuwe entiteit');?></h2>
		        </div>
		    </div>
		    <div class="row">
				 <div class="col-md-12">
					<section class="widget" id="mgmt-table-container">
						<table class="table">
							<thead>
								<th>MGMT Type</th>
								<th>Name</th>
								<th>Type</th>
								<th>Length</th>
								<th>Default</th>
								<th>Null</th>
								<th>Comments</th>
							</thead>
							<tbody id="line-table">
							
								<tr id="" class="form-line">
									<td><select class="form-control" id="mgmttype"><?=$mgmtoptions;?></select></td>
									<td><input type="text" value="" class="form-control" id="name" /></td>
									<td><select class="form-control" id="type"><?=$options;?></select></td>
									<td><input type="text" class="form-control" id="length" /></td>
									<td><input type="text" class="form-control" id="default" /></td>
									<td><input type="checkbox" class="form-control" id="null" /></td>
									<td><input type="text" class="form-control" id="comments" /></td>
								</tr>
							
							</tbody>
						</table>
						<div>
							<button type="button" id="addline" class="btn btn-primary">Add row</button>
							
							<button type="button" class="btn btn-primary">Opslaan</button>
						</div>
					</section>
				</div>
		    </div>
		</div>
	</div>
</div>
