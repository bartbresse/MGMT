<div class="wrap">
 <div class="content container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="page-title">Permissies<small></small></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <section class="widget">
                    <header>
                        <h4>
                           <i class="fa fa-file"></i>
                           Geef hieronder de standaard permissies in.
                        </h4>
                    </header>
					<br />
					<div>
						<?php
						//profiles/tables/actions
						foreach($tables as $table)
						{
							?>
							<div style="float:left;border:1px solid #ccc;">	
							<table>
							<tr><td style="width:200px;"><?=$table;?></td><td></td><td></td></tr>		
							<?
							$actionkeys = array_keys($actions);
							foreach($actionkeys as $action)
							{
								?>
								<tr>
									<td></td>
									<td><?=$actions[$action]; ?></td>
									<td>
										<select class="form-control" id="<?=$table;?>*<?=$action;?>">
											<? $keys = array_keys($profiles);
												foreach($keys as $key){ ?>				
											<option <? if($key == 'Hetworker'){ echo 'selected';}//TODO change to admin?> value="<?=$profiles[$key];?>"><?=$key;?></option>
											<? } ?>		
										</select>
									</td></tr>		
								<?	
							}
							?>
							</table>
							</div>		
							<?		
						}			
						?>
						<br style="clear:both;"/><br />
						<div class="form-actions" style="clear:both;" class="pull-right">
							<button type="submit" onclick="save({ class:'form-control',action:'<?=$this->url->get('settings/addpermissions');?>',goto:'<?=$this->url->get();?>'});" class="btn btn-primary">Opslaan</button>
						</div>
					</div>	
                </section>
            </div>
        </div>
    </div>
</div>

</div>

