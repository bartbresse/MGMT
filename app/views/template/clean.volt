{{ content() }}
	<header>
		<? if(count($indextemplates->items) > 39){ $this->partial("table/numberofresults"); } ?>		       		
	</header>
	<div>
   <? if(isset($indextemplates->items) && count($indextemplates->items) > 0){ ?>
		<table id="datatable-table" class="table table-striped">
			<thead class="table table-striped table-editable no-margin">
			   <tr>
				<th><input type="checkbox" /></th>
				<?
				$cc = 0;
				foreach($columns as $column)
				{
					?><th><a id="<?=$column;?>" <?/*class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>" */?>><?=$lang->translate($column);?></a></th><? 
					$cc++;
				}
				?>
				<th>bewerken</th>
			   </tr>
			</thead>
			<tbody class="table table-striped table-editable no-margin">
			<?	if(count($indextemplates) > 0){
					foreach($indextemplates->items as $template)
					{
					?><tr id="<?=$template->id;?>">
						<td><input type="checkbox" /></td>
						<?	
						foreach($columns as $column)
						{
							 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$template->$column;?></td><? }
						}
						?>
						<td id="bewerken" style="width:100px;">
						<?	if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get("template/view&id=".$template->id);?>"><i class="fa fa-search"></i></a><?
							}
							if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get('template/edit&id='.$template->id);?>"><i class="fa fa-edit"></i></a><?
							}
							if(in_array('delete',$actions))
							{
								?><a onclick="del('<?=$template->id;?>','<? if(isset($template->titel)){ echo $template->titel; } ?>');"><i class="fa fa-trash-o"></i></a><?
							} ?>
					   </td>
					 </tr><?		
					}
				} ?>
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
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('template/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>
		<?=$this->partial("table/export");?> 	
		<div>
<? 	$controller = 'template';
$model = 'template';	?>
<?=$this->partial("table/pagination");?> 
		</div>
	</div>




