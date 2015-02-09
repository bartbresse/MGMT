{{ content() }}
	<header>
		<? if(count($indexmessages->items) > 39){ ?>
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
   <? if(isset($indexmessages->items) && count($indexmessages->items) > 0){ ?>
		<table id="datatable-table" class="table table-striped">
			<thead class="table table-striped table-editable no-margin">
			   <tr>
				<?
				$cc = 0;
				foreach($columns as $column)
				{
					?><th><a id="<?=$column;?>" class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>"><?=$lang->translate($column);?></a></th><? 
					$cc++;
				}
				?>
				<th>bewerken</th>
			   </tr>
			</thead>
			<tbody class="table table-striped table-editable no-margin">
			<?	if(count($indexmessages) > 0){
					foreach($indexmessages->items as $message)
					{
					?><tr id="<?=$message->id;?>"><?	
						foreach($columns as $column)
												{
													 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$message->$column;?></td><? }
												}
						?>
						<td id="bewerken" style="width:100px;">
						<?	if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get("message/view&id=".$message->id);?>"><i class="fa fa-search"></i></a><?
							}
							if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get('message/edit&id='.$message->id);?>"><i class="fa fa-edit"></i></a><?
							}
							if(in_array('delete',$actions))
							{
								?><a onclick="del('<?=$message->id;?>','<? if(isset($message->titel)){ echo $message->titel; } ?>');"><i class="fa fa-trash-o"></i></a><?
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
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('message/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>						
		<?=$this->partial("table/export");?> 	
		<div>
<? 	$controller = 'indexmessages';
$model = 'message';	?>
<?=$this->partial("table/pagination");?> 
		</div>
	</div>




