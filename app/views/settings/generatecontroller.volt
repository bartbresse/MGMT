<div class="wrap">
	<div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Backend management<small></small></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="widget">
					<select onchange="load({action:'<?=$this->url->get('process/generatecontroller');?>',data:{table:this.value},container:'none'});">
						<?php foreach($tables as $table){ ?>
						<option value="<?=$table;?>"><?=$table;?></option>
						<? } ?>
					</select>
				
					<p>Kijk in de console na selectie</p>

				</section>
			</div>
		</div>
	</div>
</div>
