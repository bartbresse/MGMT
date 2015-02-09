{{ content() }}
<header>
	<? if(count($Certificaatproducts->items) > 39){ $this->partial("table/numberofresults"); } ?>			       		
</header>
<div>
<? if(isset($Certificaatproducts->items) && count($Certificaatproducts->items) > 0){ ?>
	<table id="datatable-table" class="table table-striped">
		<thead class="table table-striped table-editable no-margin">
		   <tr>
			<th><input type="checkbox" /></th>
			<? $cc = 0;
			foreach($columns as $column)
			{
				?><th><a id="<?=$column;?>" <? /*class="<? if(isset($post) && $post['key'] == $column){ echo $post['order']; } ?>" */ ?>><?=$lang->translate($column);?></a></th><? 
				$cc++;
			}
			?>
			<th>bewerken</th>
		   </tr>
		</thead>
		<tbody class="table table-striped table-editable no-margin">
		<?	$sessionuser = $this->session->get("auth");
			$clearance = $sessionuser['clearance'];			
			if(count($Certificaatproducts) > 0)
			{
				foreach($Certificaatproducts->items as $Certificaatproduct)
				{
				?><tr id="<?=$user->id;?>">
					<td><input type="checkbox" /></td>
				<?	foreach($columns as $column)
					{
						 if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$mgmtform->editField($column,$Certificaatproduct->$column);?></td><? }
					}															
					?>
					<td id="bewerken" style="width:100px;">
					<?	if(in_array('edit',$actions))
						{ ?><a href="<?php echo  $this->url->get("certificaatproduct/view&id=".$Certificaatproduct->id);?>"> <i class="fa fa-search"></i></a><? }
						if(in_array('edit',$actions))
						{ ?><a href="<?php echo  $this->url->get('certificaatproduct/edit&id='.$Certificaatproduct->id);?>"> <i class="fa fa-edit"></i></a><? }
						if(in_array('delete',$actions))
						{ ?><a alt="verwijderen" style="cursor:pointer;" onclick="del('<?=$Certificaatproduct->id;?>','certificaatproduct');"> <i class="fa fa-trash-o"></i></a><?	} 
					?>
				   </td>
				 </tr>
				 <? if(false){ ?>
				 <tr style="display:none;">
					<td>
					
					</td>
				 </tr>
				 <?	}	
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
		<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('certificaatproduct/new'.$id);?>');">
			<i class="fa fa-plus"></i> Nieuw
		</button>
	</div>
	<?=$this->partial("table/export");?> 	
	<div>
		<? 	$controller = 'Certificaatproducts';
		$model = 'certificaatproduct';	?>
		<?=$this->partial("table/pagination");?> 
	</div>
</div>