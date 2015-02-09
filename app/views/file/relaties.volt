<header>
	<h4>Groep leden/beheerders</h4>
</header>
<div class="body">
<div id="" class="form-horizontal label-left" novalidate="" data-parsley-priority-enabled="false">
	<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
		<fieldset>
		
			<div class="control-group" id="relaties_beheerdergroup">
				<label class="control-label" for="relaties_beheerder">Beheerders
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<?php echo $form->render("relaties_beheerder"); ?>
				</div>
				<script type="text/javascript">
					$(".relaties-beheerder-multiple-select").chosen({ width: '100%' });
				</script>
			</div>			
			
			<div class="control-group" id="relaties_ledengroup">
				<label class="control-label" for="relaties_leden">Leden
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<?php echo $form->render("relaties_leden"); ?>
				</div>
				<script type="text/javascript">
					$(".relaties-leden-multiple-select").chosen({ width: '100%' });
				</script>
			</div>

			
		</fieldset>    
	</div>		
</div>