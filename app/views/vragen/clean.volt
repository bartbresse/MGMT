{{ content() }}

	<header>
		<? if(count($vragens->items) > 39){ ?>
		<div class="pull-right">
			<ul class="pagination no-margin number-of-rows">
				<li class="active">
					<a>20</a>
				</li>
				 <li>
					<a>40</a>
				</li>
				 <li>
					<a>80</a>
				</li>
			</ul>
		</div>
		<? } ?>			       		
	</header>
	<div>
   <? if(isset($vragens->items) && count($vragens->items) > 0){ ?>
		<table id="datatable-table" class="table table-striped">
			<thead class="table table-striped table-editable no-margin">
			   <tr>
				<?
				$cc = 0;
				foreach($vragens->items[0] as $key => $value)
				{
					//check if column is visible
					if(in_array($key,$columns))
					{ 
						//check how/if column is sorted
						?><th><a class="<? if(isset($post) && $post['key'] == $key){ echo $post['order']; } ?>"><?=$key;?></a></th><? 
					}
					$cc++;
				}
				?>
				<th>bewerken</th>
			   </tr>
			</thead>
			<tbody class="table table-striped table-editable no-margin">
			<?
				if(count($vragens) > 0){
					foreach($vragens->items as $vragen)
					{
					?><tr id="<?=$vragen->id;?>"><?	
						foreach($vragen as $key => $value)
						{
							if(in_array($key,$columns)){ ?><td class="<?=$key;?>"><?=$value?></td><? }
						}
						?>
						<td id="bewerken" style="width:100px;">
						<?	if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get("vragen/view&id=".$vragen->id);?>"><i class="fa fa-search"></i></a><?
							}
							if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get('vragen/edit&id='.$vragen->id);?>"><i class="fa fa-edit"></i></a><?
							}
							if(in_array('delete',$actions))
							{
								?><a onclick="del('<?=$vragen->id;?>','<?=$vragen->titel;?>');"><i class="fa fa-trash-o"></i></a><?
							} ?>
					   </td>
					 </tr><?		
					}
				}
				?>
			</tbody>
		</table>
	 <? } //end if entities
		else
		{
		?>
		<table>
			<tr><td> Er zijn nog geen rijen gevonden. </td></tr>
		</table>
		<?
		} ?>
	</div>
	<div class="clearfix">		
		<div class="pull-right">
			<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('vragen/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>						
		<div>
			<select id="export-options">
				<option value="csv">Exporteren als CSV</option>
			</select>
			 <button class="btn btn-danger export-button">
				<i class="fa fa-eject"></i>
				Go
			</button>
		</div>
		<div>
<? 	$controller = 'vragens';
$model = 'vragen';	
if($$controller->total_pages > 1){  ?>
			 <ul class="pagination no-margin">
				<li><a href="<?php echo  $this->url->get($model."/"); ?>"> << </a></li>
				<li><a href="<?php echo  $this->url->get($model."/"); ?>?p=<?=$$controller->before; ?>"> < </a></li>
				<? for($i=0;$i<$$controller->total_pages;$i++)
				{ ?><li><a class="<? if($_GET['p'] == ($i+1)){ echo 'active'; } ?>" href="<?php echo $this->url->get($model."/"); ?>?p=<?=$i+1;?>"> <?=$i+1;?> </a></li><? } ?>

								<li><a href="<?php echo  $this->url->get($model."/"); ?>?p=<?=$$controller->next; ?>"> > </a></li>
								<li><a href="<?php echo  $this->url->get($model."/"); ?>?p=<?=$$controller->last; ?>"> >> </a></li>

								<li>&nbsp;&nbsp;Rij <?=($$controller->current * 20)-20;?> tot <?=($$controller->current * 20);?> van de <?=$$controller->total_items;?></li>
							</ul>	
				<? } ?>
		</div>
	</div>




