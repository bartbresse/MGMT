{{ content() }}
	<header>
		<? if(count($entities->items) > 39){ $this->partial("table/numberofresults"); } ?>			       		
	</header>
	<div>
   <? if(isset($entities->items) && count($entities->items) > 0){ ?>
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
			<tbody id="sortable" class="table table-striped table-editable no-margin">
			<?
				$sessionentitie = $this->session->get("auth");
				$clearance = $sessionentitie['clearance'];			
				if(count($entities) > 0)
				{
					foreach($entities->items as $entitie)
					{
						?><tr id="<?=$entitie->id;?>">
							<td><input type="checkbox" /></td>
							<?	
							foreach($columns as $column)
							{
                                                            if(in_array($column,$columns)){ ?><td class="<?=$column;?>"><?=$entitie->$column;?></td><? }
							}
							?>
							<td id="bewerken" style="width:100px;">
							<?	
							if($entitie->id != $sessionentitie['id']) 
							{
                                                            if(in_array('edit',$actions))
                                                            { ?><a href="<?php echo  $this->url->get("entity/view&id=".$entitie->id);?>"> <i class="fa fa-search"></i></a><?}
                                                            if(in_array('edit',$actions))
                                                            { ?><a href="<?php echo  $this->url->get('entity/edit&id='.$entitie->id);?>"> <i class="fa fa-edit"></i></a><?}?>
                                                        <?  if(in_array('delete',$actions) && $entitie->systementity == 0)
                                                            { ?><a alt="verwijderen" style="cursor:pointer;" onclick="del('<?=$entitie->id;?>','entity');"> <i class="fa fa-trash-o"></i></a><?	} 
							}
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
		<script>
			$(function() 
			{
				//preserve row width
				 $('td').each(function(){
					$(this).css('width', $(this).width() +'px');
				});
			
				$("#sortable").sortable({
					stop: function( event, ui )
					{
						ids = [];
						$('#sortable tr').each(function(){
							ids.push($(this).attr('id'));
						}); 
						save({ data:{order:ids}, action:view.baseurl+'entity/updateentityorder', goto:false});
					}
				});
				$( "#sortable" ).disableSelection();
			});
		</script>
	<?  } //end if entities
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
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('entity/newseperator');?>');">
				<i class="fa fa-plus"></i>
				Add seperator
			</button>
			<? if(isset($_GET['id'])){ $id = '&categoryid='.$_GET['id']; }else{ $id ='';} ?>
			<button class="btn btn-primary" onclick="go('<?php echo $this->url->get('entity/new'.$id);?>');">
				<i class="fa fa-plus"></i>
				Nieuw
			</button>
		</div>
		<?=$this->partial("table/export");?> 	
		<div>
<? 	$controller = 'entities';
$model = 'entity';	?>
<?=$this->partial("table/pagination");?> 
		</div>
	</div>




