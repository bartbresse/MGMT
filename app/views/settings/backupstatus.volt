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
				<header>
					<h4>
						<i class="fa fa-file-alt"></i>
					   Backup status
					</h4>
				<!--    <div class="actions hidden-xs-portrait">
						<input class="search-table" type="search" placeholder="Zoeken...">
					</div>-->
				</header>
				<div class="body">
					<div>
						<button class="btn btn-primary" onclick="save({ class:'form-control',action:'<?=$this->url->get('settings/backup');?>',goto:'<?=$this->url->get('settings/backupstatus');?>'});">
							<i class="fa fa-plus"></i>
							Backup
						</button>
						<button class="btn btn-primary" onclick="save({ class:'form-control',action:'<?=$this->url->get('settings/backup');?>',goto:'<?=$this->url->get('settings/backupstatus');?>'});">
							<i class="fa fa-plus"></i>
							Update
						</button>					
						<button class="btn btn-danger" onclick="save({ class:'form-control',action:'<?=$this->url->get('settings/backup');?>',goto:'<?=$this->url->get('settings/backupstatus');?>'});">
							<i class="fa fa-minus"></i>
							Restore
						</button>
					</div>
					<br />
					<div>
						<p>Last backup was on: <?=date('d-m-Y');?></p>
					</div>
				</div>
                </section>
            </div>
        </div>
    </div>
</div>

</div>

