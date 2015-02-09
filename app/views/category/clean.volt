{{ content() }}
	<header>
		<? if(count($indexcategorys->items) > 39){ ?>
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
   <? if(isset($indexcategorys->items) && count($indexcategorys->items) > 0){ ?>
		<table id="datatable-table" class="table table-striped">
			<thead class="table table-striped table-editable no-margin">
			   <tr>
				<?
				$cc = 0;
				foreach($columns as $column)
				{
					//voeg een column id toe om de sort te laten werken
					?><th><a id="<?=$column;?>" class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>"><?=$lang->translate($column);?></a></th><? 
					$cc++;
				}
				?>
				<th>bewerken</th>
			   </tr>
			</thead>
			<tbody class="table table-striped table-editable no-margin">
			<?	if(count($indexcategorys) > 0){
					foreach($indexcategorys->items as $category)
					{
					?><tr id="<?=$category->id;?>"><?	
						foreach($columns as $column)
						{
							 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$category->$column;?></td><? }
						}
						?>
						<td id="bewerken" style="width:100px;">
						<?	if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get("category/view&id=".$category->id);?>"><i class="fa fa-search"></i></a><?
							}
							if(in_array('edit',$actions))
							{
								?><a href="<?php echo  $this->url->get('category/edit&id='.$category->id);?>"><i class="fa fa-edit"></i></a><?
							}
							if(in_array('delete',$actions))
							{
								?><a onclick="del('<?=$category->id;?>','<? if(isset($category->titel)){ echo $category->titel; } ?>');"><i class="fa fa-trash-o"></i></a><?
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
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('category/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>						
		<?=$this->partial("table/export");?> 	
		<div>
		<? 	$controller = 'indexcategorys';
		$model = 'category';	?>
		<?=$this->partial("table/pagination");?>
		</div>
	</div>




