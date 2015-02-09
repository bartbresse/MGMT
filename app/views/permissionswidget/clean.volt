<header>
	<h4>Rechten</h4>
</header>
<div class="body">
	<div id="" class="form-horizontal label-left" novalidate="" data-parsley-priority-enabled="false">
		<input type="hidden" id="id" class="form-control" value="<? if(isset($_GET['id'])){ echo $_GET['id']; }else{ echo $entityid; } ?>" />
		<fieldset>
			<div class="control-group" id="bccgroup">
				<label class="control-label" for="bcc">Backend rechten
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<div class="col-sm-8">
						<?php echo $form->render("permission-rechten"); ?>
					</div>
				</div>
			</div>
			<p>
				Selecteer de beheer Plaatsen en teams voor deze gebruiker:
			</p>
			<div class="control-group" id="categoriegroup">
				<label class="control-label" for="bcc">
					Plaatsen en Teams
					<ul id="bccerror" class="parsley-errors-list"></ul>
				</label>
				<div class="controls form-group">
					<div class="col-sm-8">
						<?php echo $form->render("permission-beheerfunctie"); ?>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				 $(".permission-rechten-permission-beheerfunctie").chosen({ width: '100%' });
			</script>		
		</fieldset>
	</div>	
</div>




