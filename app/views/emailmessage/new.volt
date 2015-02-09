
<div class="wrap">
	<div class="content container">
		<div class="row">
		    <div class="col-md-12">
	<h2 class="page-title"><? if(isset($_GET['id'])){ echo 'Wijzig'; }else{echo 'Nieuw';} ?> email bericht</h2>
		    </div>
		</div>
		<div class="row">
			<div class="col-md-10">
				<section class="widget">	
					<div id="message-control">
						<? if(isset($_GET['id'])){ $entityid = $_GET['id']; } ?>
						<?=$this->partial("file/email");?> 
					</div>
				</section>
			</div>
		</div>
	</div>		
</div>



